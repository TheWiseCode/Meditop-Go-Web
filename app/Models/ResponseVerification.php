<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseVerification extends Model
{
    use HasFactory;

    protected $table = 'response_verifications';

    protected $fillable = [
        'time', 'response', 'detail', 'id_verification', 'id_admin'
    ];
}
