<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function revokeUser(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        //$user->tokens()->delete();
        return response()->json([
            'message' => 'Token elimnado'
        ], 200);
    }

    function getUser(Request $request)
    {
        return $request->user();
    }

    function getToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas'],
            ]);
        }
        $token = $user->createToken($request->device_name)->plainTextToken;
        return response()->json([
            'token' => $token
        ], 201);
    }
}
