<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Document;
use App\Models\Person;
use App\Models\Verification;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        //dd($data);
        /*$data = $request->validate([

        ]);*/
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
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
        $user->id_person = $person->id;
        $user->save();
        $doctor = Doctor::create([
            'reg_doctor' => $data['reg_medico'],
            'id_person' => $person->id
        ]);
        Verification::create([
            'id_doctor' => $doctor->id
        ]);
        $url = 'doctors/docs';
        $path = Storage::disk('s3')->put($url, $data['curriculum']);
        Storage::disk('s3')->setVisibility($path, 'public');
        $cur_url = Storage::disk('s3')->url($path);
        Document::create([
            'name' => 'Curriculum',
            'url' => $cur_url,
            'id_doctor' => $doctor->id
        ]);
        for ($i = 0; $i < count($data['name_docs']); $i++) {
            $name = $data['name_docs'][$i];
            $doc = $data['docs'][$i];
            $path = Storage::disk('s3')->put($url, $doc);
            Storage::disk('s3')->setVisibility($path, 'public');
            $doc_url = Storage::disk('s3')->url($path);
            Document::create([
                'name' => $name,
                'url' => $doc_url,
                'id_doctor' => $doctor->id
            ]);
        }
        return $user;
    }
}
