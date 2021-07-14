<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class Transaction extends Model
{
    protected $collection = 'transactions';

    protected $fillable = [
        'type',
        'time_transaction',
        'amount',
        'coin_type',
        'id_account'
    ];

    public function account(){
        return $this->belongsTo(Account::class);
    }
}
