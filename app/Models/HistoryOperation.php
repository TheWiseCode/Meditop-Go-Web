<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryOperation extends Model
{
    use HasFactory;

    protected $table = 'history_operations';

    protected $fillable = [
        'doctor_operation', 'description', 'date', 'id_patient'
    ];
}
