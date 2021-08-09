<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Consult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultController extends Controller
{
    public function index()
    {
        $doc = auth()->user()->getDoctor();
        $consults = Consult::join('reservations', 'reservations.id', 'consults.id_reservation')
            ->join('patients', 'patients.id', 'reservations.id_patient')
            ->join('offer_specialties', 'offer_specialties.id', 'reservations.id_offer')
            ->join('specialties', 'specialties.id', 'offer_specialties.id_specialty')
            ->join('persons', 'persons.id' ,'patients.id_person')
            ->join('doctors', 'doctors.id', 'consults.id_doctor')
            ->select(
                'consults.id', 'specialties.name as name_specialty',
                DB::raw("concat(persons.name,' ', persons.last_name) as name_complete"),
                'consults.time'
            )
            ->where('doctors.id', $doc->id)
            ->where('consults.state', 'aceptada')
            ->orderby('consults.time')
            ->get();
        //dd($consults);
        return view('doctor.consults.index', compact('consults'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Consult $consult)
    {
        return view('doctor.consults.show', compact('consult'));
    }

    public function edit(Consult $consult)
    {
        //
    }

    public function update(Request $request, Consult $consult)
    {
        //
    }

    public function destroy(Consult $consult)
    {
        //
    }
}
