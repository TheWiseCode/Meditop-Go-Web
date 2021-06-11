<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model as Model;

class Person extends Model
{
    //use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'persons';

    protected $fillable = [
        'name',
        'last_name',
        'ci',
        'cellphone',
        'birthday',
        'sex',
        'email',
        'password'
    ];
}
