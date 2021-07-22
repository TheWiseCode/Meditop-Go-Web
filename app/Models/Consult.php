<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consult extends Model
{
    use HasFactory;

    protected $table = 'consults';
    protected $fillable = [
        'time', 'state', 'url_jitsi', 'min_duration',
        'id_doctor', 'id_patient', 'id_reservation'
    ];
}
