<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Person extends Model
{
    //use HasFactory;

    protected $collection = 'persons';

    protected $fillable = [
        'name',
        'last_name',
        'ci',
        'cellphone',
        'birthday',
        'sex',
        'email'
    ];
}
