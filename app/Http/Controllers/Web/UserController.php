<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Person;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $persons = Person::join('doctors', 'doctors.id_person', 'persons.id')
            ->join('patients', 'patients.id_person', 'persons.id')
            ->get();
        $persons = Person::orderBy('id')->get();
        return view('admin.users.index', compact('persons'));
    }

    public function createAdmin()
    {
        return view('admin.users.create-admin');
    }

    public function storeAdmin(Request $request)
    {
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
            'email_verified_at' => Carbon::now()
        ]);
        Admin::create([
            'id_person' => $person->id,
            'profession' => $data['profession']
        ]);
        return redirect()->route('users.index')->with(['gestion' => 'Nuevo administrador registrado']);
    }

    public function doctorRequests(){
        $persons = Person::orderBy('id')->get();
        return view('admin.users.doctor-requests', compact('persons'));
    }
}
