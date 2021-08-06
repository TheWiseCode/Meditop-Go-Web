<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ActionReservation;
use App\Models\Consult;
use App\Models\Doctor;
use App\Models\OfferSpecialty;
use App\Models\Reservation;
use App\Notifications\ReservationNotify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::join('patients', 'patients.id', 'reservations.id_patient')
            ->join('persons', 'persons.id', 'patients.id_person')
            ->join('offer_specialties', 'offer_specialties.id', 'reservations.id_offer')
            ->join('specialties', 'specialties.id', 'offer_specialties.id_specialty')
            ->select(
                'reservations.id', 'specialties.name as name_specialty',
                DB::raw("concat(persons.name,' ', persons.last_name) as name_complete"),
                DB::raw("reservations.time_consult"),
                'reservations.time_reservation'
            )
            ->where('state', 'pendiente')
            ->orderby('time_reservation')
            ->get();
        return view('doctor.reservations.index', compact('reservations'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }


    public function show(Reservation $reservation)
    {
        //
    }

    public function edit(Reservation $reservation)
    {
        //
    }

    public function update(Request $request, Reservation $reservation)
    {
        //
    }

    public function destroy(Reservation $reservation)
    {
        //
    }

    public function verifiedReservation(Request $request)
    {
        $data = $request->validate([
            'id_offer' => 'required|exists:offer_specialties,id',
            'datetime' => 'required|date',
        ]);
        $reserve = Reservation::join('offer_specialties', 'offer_specialties.id', 'reservations.id_offer')
            ->where('time_consult', $data['datetime'])->first();
        if ($reserve) {
            return response(['message' => 'Horario no disponible'], 406);
        }
        return response(['message' => 'Horario disponible'], 200);
    }

    public function doReservation(Request $request)
    {
        $data = $request->validate([
            'id_patient' => 'required',
            'id_offer' => 'required',
            'datetime' => 'required|date',
        ]);
        $reserve = Reservation::join('offer_specialties', 'offer_specialties.id', 'reservations.id_offer')
            ->where('time_consult', $data['datetime'])->first();
        if ($reserve) {
            return response(['message' => 'Horario no disponible'], 406);
        }
        $res = Reservation::create([
            'state' => 'pendiente',
            'time_consult' => $data['datetime'],
            'id_offer' => $data['id_offer'],
            'id_patient' => $data['id_patient'],
        ]);
        $offer = OfferSpecialty::where('id', $data['id_offer'])->first();
        $user = Doctor::getUser($offer->id_doctor);
        $user->notify(new ReservationNotify());
        return response(['message' => 'Reservacion realizada'], 200);
    }

    public function acceptReservation(Request $request)
    {
        $data = $request->validate([
            'id_reservation' => 'required'
        ]);
        ActionReservation::create([
            'detail' => 'Solicitud de reservacion aceptada',
            'action' => Carbon::now(),
            'type' => 'Aceptacion de reservacion',
            'id_reservation' => $data['id_reservation']
        ]);
        $res = Reservation::find($data['id_reservation']);
        $res->state = 'aceptada';
        $res->save();
        $offer = OfferSpecialty::join('reservations', 'reservations.id_offer', 'offer_specialties.id')
            ->select('offer_specialties.*')
            ->where('reservations.id', $res->id)->first();
        $str = 'meditopgo' . $res->id . '-' . Str::random(10);
        Consult::create([
            'time' => $res->time_consult,
            'url_jitsi' => 'https://meet.jit.si/' . $str,
            'min_duration' => 30,
            'id_doctor' => $offer->id_doctor,
            'id_patient' => $res->id_patient,
            'id_reservation' => $data['id_reservation']
        ]);
        return redirect()->route('reservations.index')->with(['gestion' => 'Reservacion aceptada']);
    }

    public function deniedReservation(Request $request)
    {
        $data = $request->validate([
            'id_reservation' => 'required',
            'detail' => 'required'
        ]);
        ActionReservation::create([
            'detail' => $data['detail'],
            'action' => Carbon::now(),
            'type' => 'Rechazo reservacion',
            'id_reservation' => $data['id_reservation']
        ]);
        $res = Reservation::find($data['id_reservation']);
        $res->state = 'rechazada';
        $res->save();
        return redirect()->route('reservations.index')->with(['gestion' => 'Reservacion rechazada']);
    }
}
