<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class Transaction extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'transactions';

    protected $fillable = [
        'type',
        'time_transaction',
        'amount',
        'coin_type',
        'id_account'
    ];
}
