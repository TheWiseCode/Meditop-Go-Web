<?php

namespace App\Http\Controllers\Web\Web\Api;

use App\Http\Controllers\Web\Web\Controller;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class AccountController extends Controller
{
    public function abrir(Request $request)
    {
        $id_user = $request->user()->id;
        $opened_account = Carbon::now();
        $time = new UTCDateTime($opened_account->getTimestamp() * 1000);
        $id = new  ObjectId($id_user);
        $number = Account::getNewNumber();
        $account = Account::create([
            'number' => strval($number),
            'balance' => 0,
            'opened_account' => $time,
            'id_user' => $id
        ]);
        return response([
            'message' => 'Apertura de cuenta exitosa',
            'account' => $account
        ], 201);
    }

    public function getCuentas(Request $request)
    {
        $id_user = $request->user()->id;
        $accounts = Account::all();
        $result = [];
        foreach ($accounts as $acc) {
            if ($acc->id_user == $id_user)
                array_push($result, $acc);
        }
        return response([
            'data' => $result
        ], 200);
    }
}
