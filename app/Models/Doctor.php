<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctors';
    protected $fillable = [
        'id_person',
        'reg_doctor',
        'verified'
    ];

    public static function getUser($id)
    {
        return User::join('persons', 'persons.id', 'users.id_person')
            ->join('doctors', 'doctors.id_person', 'persons.id')
            ->select('users.*')
            ->where('doctors.id', $id)->first();
    }

    public static function getPerson($id)
    {
        return Person::join('doctors', 'doctors.id_persons', 'persons.id')
            ->select('persons.*')
            ->where('doctors.id', $id)->first();
    }
}
