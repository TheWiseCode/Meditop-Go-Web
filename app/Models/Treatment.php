<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    use HasFactory;

    protected $table = 'treatments';
    protected $fillable = [
        'id_medicine', 'id_prescription', 'schedule', 'days', 'amount', 'detail'
    ];
}
