<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Document;
use App\Models\Person;
use App\Models\ResponseVerification;
use App\Models\User;
use App\Models\Verification;
use App\Notifications\TenantCreated;
use App\Notifications\VerificationNotify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {

    }

    public function validar()
    {
        if (!auth()->user()->isAdmin()) {
            abort(401);
        }
    }

    public function index()
    {
        $this->validar();
        $persons = Person::join('users', 'users.id_person', 'persons.id')
            ->select('persons.*', DB::raw('users.id as id_user'))
            ->orderBy('id')->get();
        return view('admin.users.index', compact('persons'));
    }

    public function createAdmin()
    {
        $this->validar();
        return view('admin.users.create-admin');
    }

    public function show(User $user)
    {
        $person = $user->getPerson();
        $docs = null;
        if ($person->isDoctor()) {
            $person = Person::join('doctors', 'doctors.id_person', 'persons.id')
                ->select('persons.*', 'doctors.reg_doctor', DB::raw('doctors.id as id_doctor'))
                ->where('doctors.id_person', $person->id)
                ->first();
            $docs = Document::where('id_doctor', $person->id_doctor)->get();
        } else if ($person->isAdmin()) {
            $person = Person::join('admins', 'admins.id_person', 'persons.id')
                ->select('persons.*', 'admins.profession')
                ->where('admins.id_person', $person->id)
                ->first();
        } else {//Paciente
            $person = Person::join('patients', 'patients.id_person', 'persons.id')
                ->select('persons.*', 'patients.blood_type', 'patients.allergy')
                ->where('patients.id_person', $person->id)
                ->first();
        }
        return view('admin.users.show', compact('person', 'docs'));
    }

    public function storeAdmin(Request $request)
    {
        $this->validar();
        $data = $request->validate([
            'name' => 'required|string|min:3',
            'last_name' => 'required|string',
            'ci' => 'required|string',
            'cellphone' => 'required|string',
            'birthday' => 'required|date',
            'sex' => 'required|string',
            'email' => 'required|email|unique:users|unique:persons',
            'profession' => 'required|string'
        ]);
        $person = Person::create([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'ci' => $data['ci'],
            'cellphone' => $data['cellphone'],
            'birthday' => $data['birthday'],
            'sex' => $data['sex'],
            'email' => $data['email'],
        ]);
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['ci']),
            'id_person' => $person->id,
            'email_verified_at' => now(),
        ]);
        Admin::create([
            'id_person' => $person->id,
            'profession' => $data['profession']
        ]);
        return redirect()->route('users.index')->with(['gestion' => 'Nuevo administrador registrado']);
    }

    public function doctorRequests()
    {
        $this->validar();
        $doctors = Doctor::join('persons', 'persons.id', 'doctors.id_person')
            ->join('verifications', 'verifications.id_doctor', 'doctors.id')
            ->select('persons.*', DB::raw('verifications.state'))
            ->where('verifications.state', 'pendiente')
            ->where('verified', false)->get();
        return view('admin.users.doctor-requests', compact('doctors'));
    }

    public function doctorVerification(Person $person)
    {
        $person = Person::join('doctors', 'doctors.id_person', 'persons.id')
            ->select('persons.*', 'doctors.reg_doctor', DB::raw('doctors.id as id_doctor'))
            ->where('doctors.id_person', $person->id)
            ->first();
        $docs = Document::where('id_doctor', $person->id_doctor)->get();
        //dd($docs);
        return view('admin.users.doctor-verification', compact('person', 'docs'));
    }

    public function acceptVerification(Request $request)
    {
        $user = auth()->user();
        $person = $user->getPerson();
        $admin = Admin::where('id_person', $person->id)->first();
        $doc = Doctor::where('id_person', $request->person_id)->first();
        $ver = Verification::where('id_doctor', $doc->id)
            ->where('state', 'pendiente')->first();
        ResponseVerification::create([
            'response' => true,
            'detail' => 'Verificacion valida, documentos validos',
            'id_verification' => $ver->id,
            'id_admin' => $admin->id
        ]);
        $ver->state = 'aceptada';
        $ver->save();
        $doc->verified = true;
        $doc->save();
        $user->notify(new VerificationNotify(true));
        return redirect()->route('users.index')->with(['gestion' => 'Medico verificado']);
    }

    public function deniedVerification(Request $request)
    {
        $user = auth()->user();
        $person = $user->getPerson();
        $admin = Admin::where('id_person', $person->id)->first();
        $doc = Doctor::where('id_person', $request->person_id)->first();
        $ver = Verification::where('id_doctor', $doc->id)
            ->where('state', 'pendiente')->first();
        ResponseVerification::create([
            'response' => false,
            'detail' => $request->detail,
            'id_verification' => $ver->id,
            'id_admin' => $admin->id
        ]);
        $ver->state = 'rechazada';
        $ver->save();
        $user->notify(new VerificationNotify(false, $request->detail));
        return redirect()->route('users.index')->with(['gestion' => 'Medico rechazado']);
    }
}
