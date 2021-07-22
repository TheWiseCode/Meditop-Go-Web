<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionReservation extends Model
{
    use HasFactory;

    protected $table = 'action_reservations';
    protected $fillable = [
        'detail', 'action', 'type', 'id_reservation'
    ];
}
