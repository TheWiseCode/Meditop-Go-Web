<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferDays extends Model
{
    use HasFactory;

    protected $table = 'offer_days';
    protected $fillable = [
        'id_offer', 'id_day'
    ];
}
