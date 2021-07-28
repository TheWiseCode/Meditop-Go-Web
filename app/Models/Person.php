<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = 'persons';

    protected $fillable = [
        'name',
        'last_name',
        'ci',
        'cellphone',
        'birthday',
        'sex',
        'email'
    ];

    public function isAdmin(): bool
    {
        $person = Person::join('admins', 'admins.id_person', 'persons.id')
            ->where('admins.id_person', $this->id)
            ->first();
        return $person != null;
    }

    public function isDoctor(): bool
    {
        $person = Person::join('doctors', 'doctors.id_person', 'persons.id')
            ->where('doctors.id_person', $this->id)
            ->first();
        return $person != null;
    }

    public function isPatient(): bool
    {
        $person = Person::join('patients', 'patients.id_person', 'persons.id')
            ->where('patients.id_person', $this->id)
            ->first();
        return $person != null;
    }

    public function isOwner(): bool
    {
        $admin = Admin::join('persons', 'admins.id_person', 'persons.id')
            ->where('admins.id_person', $this->id)
            ->first();
        return $admin != null && $admin->isOwner();
    }

    public static function sexo($sex){
        switch ($sex){
            case 'M':
                return 'Masculino';
                case 'F':
                return 'Femenino';
            default:
                return 'Otro';
        }
    }
}
