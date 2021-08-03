<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'id_person'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getPerson(){
        $person =  Person::join('users', 'users.id_person', 'persons.id')
            ->select('persons.*')
            ->where('users.id', $this->id)
            ->first();
        return $person;
    }

    public function getDoctor(){
        return $this->getPerson()->getDoctor();
    }

    public function getAdmin(){
        return $this->getPerson()->getAdmin();
    }

    public function isAdmin(){
        return $this->getPerson()->isAdmin();
    }

    public function isOwner(){
        return $this->getPerson()->isOwner();
    }
}
