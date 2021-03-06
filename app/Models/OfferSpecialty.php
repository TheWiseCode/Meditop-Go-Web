<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferSpecialty extends Model
{
    use HasFactory;

    protected $table = 'offer_specialties';
    protected $fillable = [
        'time_start', 'time_end', 'id_doctor', 'id_specialty'
    ];
}
