<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class AccountController extends Controller
{
    public function abrir(Request $request){
        $data = $request->validate([
            'number' => 'required|string|min:4',
            'id_user' => 'required|string'
        ]);
        $opened_account = Carbon::now();
        $time = new UTCDateTime($opened_account->getTimestamp()*1000);
        $id = new  ObjectId($data['id_user']);
        $account = Account::create([
            'number' => $data['number'],
            'balance' => 0,
            'opened_account' => $time,
            'id_user' => $id
        ]);
        return response([
            'message' => 'Apertura de cuenta exitosa',
            'account' => $account
        ], 201);
    }
}
