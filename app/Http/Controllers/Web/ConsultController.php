<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ActionReservation;
use App\Models\Consult;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $doc = auth()->user()->getDoctor();
        $consults = Consult::join('reservations', 'reservations.id', 'consults.id_reservation')
            ->join('patients', 'patients.id', 'reservations.id_patient')
            ->join('offer_specialties', 'offer_specialties.id', 'reservations.id_offer')
            ->join('specialties', 'specialties.id', 'offer_specialties.id_specialty')
            ->join('persons', 'persons.id', 'patients.id_person')
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
        return view('doctor.consults.index', compact('consults', 'now'));
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

    public function cancelConsult(Request $request)
    {
        $data = $request->validate([
            'id_consult' => 'required',
            'detail' => 'required'
        ]);
        $con = Consult::find($data['id_consult']);
        $con->state = 'cancelada';
        $con->save();
        $res = Reservation::find($con->id_reservation);
        $res->state = 'cancelada';
        $res->save();
        ActionReservation::create([
            'detail' => $data['detail'],
            'action' => Carbon::now(),
            'type' => 'Cancelacion de consulta',
            'id_reservation' => $con->id_reservation
        ]);
        return redirect()->route('consults.index')->with(['gestion' => 'Consulta cancelada']);
    }
}
