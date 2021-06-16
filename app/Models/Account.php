<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class Account extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'accounts';

    protected $fillable = [
        'number',
        'balance',
        'opened_account',
        'id_user'
    ];
}
