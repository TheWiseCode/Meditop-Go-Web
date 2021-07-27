<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(){

    }

    public function validar(){
        if(!auth()->user()->isAdmin()){
            abort(401);
        }
    }

    public function index(){
        $this->validar();
        $persons = Person::join('doctors', 'doctors.id_person', 'persons.id')
            ->join('patients', 'patients.id_person', 'persons.id')
            ->get();
        $persons = Person::orderBy('id')->get();
        return view('admin.users.index', compact('persons'));
    }
}
