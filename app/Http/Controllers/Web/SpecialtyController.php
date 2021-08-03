<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Specialty $specialty)
    {
        //
    }

    public function edit(Specialty $specialty)
    {
        //
    }

    public function update(Request $request, Specialty $specialty)
    {
        //
    }

    public function destroy(Specialty $specialty)
    {
        //
    }

    public function getAll(){
        $specialties = Specialty::join('offer_specialties', 'offer_specialties.id_specialty', 'specialties.id')
            ->select('specialties.id', 'specialties.name')
            ->get();
        return response($specialties, 200);
    }
}
