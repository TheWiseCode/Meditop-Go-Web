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
        return "Correo verificado";
        //return response(['message' => 'Correo verificado'], 200);
    }

    public function notification(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    }

    public function resend(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email'
        ]);
        $user = User::where('email', $data['email'])->first();
        if($user) {
            $user->sendEmailVerificationNotification();
            return response(['message' => 'Correo de verificiacion enviado'], 200);
        }else{
            return response(['message' => 'Error correo no registrado'], 406);
        }
    }
}
