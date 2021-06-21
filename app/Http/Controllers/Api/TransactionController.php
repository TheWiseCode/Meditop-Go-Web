<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class TransactionController extends Controller
{
    public function abonar(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|string',
            'amount' => 'required|numeric',
            'coin_type' => 'required|string|min:1',
            'id_account' => 'required|string'
        ]);
        $account = Account::findId($data['id_account']);
        //Abonar saldo
        $account->balance += $data['amount'];
        $account->save();
        $date = Carbon::now();
        $mongo_date = new UTCDateTime($date->getTimestamp()*1000);
        $id = new  ObjectId($data['id_account']);
        $tran = Transaction::create([
            'type' => $data['type'],
            'time_transaction' => $mongo_date,
            'amount' => $data['amount'],
            'coin_type' => $data['coin_type'],
            'id_account' => $id
        ]);
        return response([
            'message' => 'Transaccion de deposito realizada',
            'transaction' => $tran
        ], 201);
    }

    public function retirar(Request $request){
        $data = $request->validate([
            'type' => 'required|string',
            'amount' => 'required|numeric',
            'coin_type' => 'required|string|min:1',
            'id_account' => 'required|string'
        ]);
        $account = Account::findId($data['id_account']);
        if($account->balance < $data['amount']){
            return response([
                'message' => 'Saldo insuficiente para retirar'
            ], 406);
        }
        //Descontar saldo
        $account->balance -= $data['amount'];
        $account->save();
        $date = Carbon::now();
        $mongo_date = new UTCDateTime($date->getTimestamp()*1000);
        $tran = Transaction::create([
            'type' => $data['type'],
            'time_transaction' => $mongo_date,
            'amount' => $data['amount'],
            'coin_type' => $data['coin_type'],
            'id_account' => new ObjectId($data['id_account'])
        ]);
        return response([
            'message' => 'Transaccion de retiro realizada',
            'transaction' => $tran
        ], 201);
    }
}
