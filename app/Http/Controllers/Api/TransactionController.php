<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
            'message' => 'Transaccion realizada',
            'transacton' => $tran
        ], 201);
    }

    public function retirar(Request $request){
        $data = $request->validate([
            'type' => 'required|string',
            'amount' => 'required|numeric',
            'coin_type' => 'required|string|min:1',
            'id_account' => 'required|string'
        ]);
        if($data['wdw']){//Logica tiene saldo

            return response([
                'message' => 'Saldo insuficiente para retirar'
            ], 406);
        }
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
            'message' => 'Transaccion realizada',
            'transacton' => $tran
        ], 201);
    }
}
