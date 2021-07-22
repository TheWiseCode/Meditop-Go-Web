<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferSpecialty extends Model
{
    use HasFactory;

    protected $table = 'offer_specialties';
    protected $fillable = [
        'schedule_days', 'schedule_time', 'id_doctor', 'id_disease'
    ];
}
