<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientDisease extends Model
{
    use HasFactory;

    protected $table = 'patient_diseases';

    protected $fillable = [
        'id_disease', 'id_patient'
    ];
}
