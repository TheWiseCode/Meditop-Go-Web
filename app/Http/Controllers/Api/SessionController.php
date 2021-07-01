<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Person;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
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
            'email' => 'required|email|unique:users,persons',
            'password' => 'required|string|confirmed',
            'token_name' => 'string'
        ]);
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'])
            ]);
            $opened_account = Carbon::now();
            $time = new UTCDateTime($opened_account->getTimestamp() * 1000);
            $idd = new  ObjectId($user->id);
            $number = Account::getNewNumber();
            $account = Account::create([
                'number' => strval($number),
                'type' => 'Caja de ahorro',
                'balance' => 0,
                'opened_account' => $time,
                'id_user' => $idd
            ]);
            $date = new DateTime($data['birthday']);
            $mongo_date = new UTCDateTime($date->getTimestamp() * 1000);
            //DateTime::createFromFormat()
            $person = Person::create([
                'name' => $data['name'],
                'last_name' => $data['last_name'],
                'ci' => $data['ci'],
                'cellphone' => $data['cellphone'],
                'birthday' => $mongo_date,
                'sex' => $data['sex'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']), 'token_name' => 'string'
            ]);
            $token = $user->createToken($data['token_name'])->plainTextToken;
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
            'password' => 'required|string',
            'token_name' => 'string'
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
                'message' => 'Contraseña incorrecta',
            ], 401);
        }
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
        $person = Person::where('email', $request->user()->email)->first();
        //dd($request->user()->id);
        return $person;
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return [
            'message' => 'Sesion cerrada'
        ];
    }
}

