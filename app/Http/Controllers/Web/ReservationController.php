<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Carbon\Carbon;
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
            'date' => 'required|date',
            'time' => 'required|date_format:H:i'
        ]);
        $datetime = $data['date'] . ' ' . $data['time'] . ':00';
        $reserve = Reservation::join('offer_specialties', 'offer_specialties.id', 'reservations.id_offer')
            ->where('time_consult', $datetime)->first();
        if ($reserve) {
            return response(['message' => 'Horario no disponible'], 406);
        }
        return response(['message' => 'Horario disponible'], 200);
    }
}
