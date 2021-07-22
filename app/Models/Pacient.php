<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pacient extends Model
{
    use HasFactory;

    protected $table = 'patients';
    protected $fillable = [
        'id_person',
        'blood_type',
        'allergy'
    ];
}
