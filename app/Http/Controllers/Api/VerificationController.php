<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{

    public function showVerify()
    {
        return view('auth.verify-email');
    }

    public function verify(Request $request, $id)
    {
        if (!$request->hasValidSignature()) {
            return response()->json(["msg" => "Invalid/Expired url provided."], 401);
        }
        $user = User::findOrFail($id);
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
        if (!$user->getPerson()->isPatient()) {
            return redirect()->to('/home');
        }
        return response(['message' => 'Correo enviado'], 200);
        //return redirect()->to('/');
    }

    public function notification(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    }

    public function resend()
    {

    }
}
