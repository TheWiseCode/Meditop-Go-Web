<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Exception;

class SessionController extends Controller
{

    public function registerPerson(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string',
            'last_name' => 'required|string',
            'ci' => 'required|string|min:4',
            'cellphone' => 'required|string|min:4',
            'birthday' => 'required|date',
            'sex' => 'required|string|min:1|max:1',
            'email' => 'required|email|unique:users,persons',
            'password' => 'required|string|confirmed'
        ]);
        if ($validation->fails()) {
            return response(['error' => ['message' => 'Errores en parametros de peticion']],
                500);
        }
        try {
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password'))
            ]);

            $person = Person::create([
                'name' => $request->input('name'),
                'last_name' => $request->input('last_name'),
                'ci' => $request->input('ci'),
                'cellphone' => $request->input('cellphone'),
                'birthday' => $request->input('birthday'),
                'sex' => $request->input('sex'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password'),)
            ]);
            $token = $user->createToken('token' . $user->name)->plainTextToken;
            $response = [
                'person' => $person,
                'token' => $token
            ];
            return response($response, 201);
        } catch (Exception $e) {
            return response(['error' => ['message' => 'Error registro no completado']],
                500);
        }
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            return response([
                'message' => 'Correo electronico no encontrado',
            ], 401);
        }
        if(!Hash::check($data['password'], $user->password)){
            return response([
                'message' => 'Contraseña incorrecta',
            ], 401);
        }
        $token = $user->createToken('token'.$user->name)->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response, 201);
    }
}

