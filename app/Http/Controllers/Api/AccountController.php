<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function abrirCuenta(Request $request){
        $data = $request->validate([
            'number' => 'required|string|min:4',
            'balance' => 'required|numeric|min:0',
            'id_user' => 'required|string'
        ]);
        $opened_account = Carbon::now();
    }
}
