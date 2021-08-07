<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationDevice extends Model
{
    use HasFactory;

    protected $table = 'notifications_devices';

    protected $fillable = [
        'name_device', 'token'
    ];
}
