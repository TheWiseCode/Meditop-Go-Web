<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patients';
    protected $fillable = [
        'id_person',
        'blood_type',
        'allergy'
    ];

    public static function getUser($id)
    {
        return User::join('persons', 'persons.id', 'users.id_person')
            ->join('patients', 'patients.id_person', 'persons.id')
            ->select('users.*')
            ->where('patients.id', $id)->first();
    }

    public static function getPerson($id)
    {
        return Person::join('patients', 'patients.id_persons', 'persons.id')
            ->select('persons.*')
            ->where('patients.id', $id)->first();
    }
}
