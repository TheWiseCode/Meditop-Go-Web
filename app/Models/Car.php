<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Car extends Model
{
    //protected $connection = 'mongodb';
    //protected $collection = 'cars';

    protected $fillable = [
        'carcompany', 'model', 'price'
    ];
}
