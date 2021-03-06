<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NotificationDevice;
use App\Models\Patient;
use App\Models\Person;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Exception;

class SessionController extends Controller
{

    public function registerPerson(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'last_name' => 'required|string',
            'ci' => 'required|string|min:4',
            'cellphone' => 'required|string|min:4',
            'birthday' => 'required|date',
            'sex' => 'required|string|min:1|max:1',
            'allergies' => 'required',
            'type_blood' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|confirmed',
            'token_name' => 'string'
        ]);
        $email = User::where('email', $data['email'])->first();
        if ($email) {
            return response(['message' => 'Error correo ya registrado'],
                406);
        }
        try {
            $person = Person::create([
                'name' => $data['name'],
                'last_name' => $data['last_name'],
                'ci' => $data['ci'],
                'cellphone' => $data['cellphone'],
                'birthday' => $data['birthday'],
                'sex' => $data['sex'],
                'email' => $data['email'],
                //'password' => Hash::make($data['password']),
                //'token_name' => 'string'
            ]);
            Patient::create([
                'id_person' => $person->id,
                'blood_type' => $data['type_blood'],
                'allergy' => $data['allergies']
            ]);
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'id_person' => $person->id
            ])->sendEmailVerificationNotification();
            /*$token = $user->createToken($data['token_name'])->plainTextToken;
            $response = [
                'person' => $person,
                'token' => $token
            ];*/
            //return response($response, 201);
            return response(['message' => 'Se le ha enviado un correo de verificaci??n, verifiquese para poder ingresar a la app'], 201);
        } catch (Exception $e) {
            return response(['message' => 'Error registro no completado'],
                406);
        }
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'token_name' => 'required|string',
            'token_firebase' => 'required|string'
        ]);
        $person = Person::where('email', $data['email'])->first();
        if (!$person) {
            return response([
                'message' => 'Correo electronico no encontrado',
            ], 401);
        }
        $user = User::where('email', $person->email)->first();
        if (!Hash::check($data['password'], $user->password)) {
            return response([
                'message' => 'Contrase??a incorrecta',
            ], 401);
        }
        if ($user->email_verified_at == null) {
            return response([
                'message' => 'Verifique su correo para poder ingresar',
            ], 402);
        }
        NotificationDevice::create([
            'name_device' => $data['token_name'],
            'token' => $data['token_firebase'],
            'id_user' => $user->id
        ]);
        $token = $user->createToken($data['token_name'])->plainTextToken;
        $response = [
            'person' => $person,
            'token' => $token
        ];
        return response($response, 201);
        /*$user->tokens()->delete();
        return response()->json([
            'message' => 'Token elimnado'
        ],201);*/
    }

    public function getUser(Request $request)
    {
        $person = Person::join('patients', 'patients.id_person', 'persons.id')
            ->join('users', 'users.id_person', 'persons.id')
            ->select('patients.id as id_patient', 'persons.*', 'users.id as id_user')
            ->where('users.email', $request->user()->email)->first();
        return $person;
    }

    public function logout(Request $request)
    {
        $data = $request->validate([
            'token_firebase' => 'required'
        ]);
        NotificationDevice::where('token', $data['token_firebase'])->delete();
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return [
            'message' => 'Sesion cerrada'
        ];
    }

    public function findEmail(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email'
        ]);
        $email = User::where('email', $data['email'])->first();
        if ($email != null) {
            return response(
                ['message' => 'Correo ya registrado']
                , 406);
        }
        return response(['message' => 'Correo valido', 'email' => $email, 'data' => $data])
            ->header('Content-type', 'text/plain');
    }
}

