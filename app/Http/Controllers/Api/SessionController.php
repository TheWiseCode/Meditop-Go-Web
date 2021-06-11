<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Exception;

class SessionController extends Controller
{

    public function registerPerson(Request $request)
    {
        //($request->request);
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'last_name' => 'required|string',
                'ci' => 'required|string|min:4',
                'cellphone' => 'required|string|min:4',
                'birthday' => 'required|date',
                'sex' => 'required|string|min:1|max:1',
                'email' => 'required|email|unique:users,persons',
                'password' => 'required|string|confirmed'
            ]);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'])
            ]);

            $person = Person::create([
                'name' => $data['name'],
                'last_name' => $data['last_name'],
                'ci' => $data['ci'],
                'cellphone' => $data['cellphone'],
                'birthday' => new DateTime($data['birthday']),
                'sex' => $data['sex'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'])
            ]);

            $token = $user->createToken('token' . $data['name'])->plainTextToken;
            $response = [
                'person' => $person,
                'token' => $token
            ];
            return response($response, 201);
        } catch (Exception $e) {
            return response(["error" => ["message" => "Error registro no completado."]],
                500);
        }
    }
}

