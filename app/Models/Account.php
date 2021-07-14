<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class Account extends Model
{
    protected $collection = 'accounts';

    protected $fillable = [
        'number',
        'type',
        'balance',
        'opened_account',
        'id_user'
    ];

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public static function findId($id)
    {
        /*$acc = Account::whereRaw([
            '_id' => ['60ca9543ce4400009a0000ab']
        ])->get();*/

        //$amount = DB::collection('accounts')->select(['balance'])->where('_id', '60ca8b1ace4400009a0000a6')->first()['_id'];
        $account = null;
        $all_accounts = Account::all();
        foreach ($all_accounts as $acc) {
            if ($acc->id == $id) {
                $account = $acc;
                break;
            }
        }
        return $account;
    }

    public static function getNewNumber()
    {
        $account = Account::select('number')->orderBy('number', 'desc')->first();
        if($account == null)
            return 1000;
        return intval($account->number) + 1;
    }
}
