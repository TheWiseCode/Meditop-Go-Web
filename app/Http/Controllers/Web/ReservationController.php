<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\OfferSpecialty;
use App\Models\Reservation;
use App\Notifications\ReservationNotify;
use Illuminate\Http\Request;

class ReservationController extends Controller
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
}
