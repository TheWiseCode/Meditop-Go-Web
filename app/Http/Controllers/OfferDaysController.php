<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\OfferDays;
use App\Models\OfferSpecialty;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OfferDaysController extends Controller
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

    public function show(OfferDays $offerDays)
    {
        //
    }

    public function edit(OfferDays $offerDays)
    {
        //
    }

    public function update(Request $request, OfferDays $offerDays)
    {
        //
    }

    public function destroy(OfferDays $offerDays)
    {
        //
    }

    public function getBySpecialty(Request $request)
    {
        $data = $request->validate([
            'id_specialty' => 'required|exists:specialties,id'
        ]);
        $response = [];
        $dat = Doctor::join('offer_specialties', 'offer_specialties.id_doctor', 'doctors.id')
            ->join('persons', 'persons.id', 'doctors.id_person')
            ->select('offer_specialties.id as id_offer', 'persons.*',
                'offer_specialties.time_start', 'offer_specialties.time_end')
            ->where('offer_specialties.id_specialty', $data['id_specialty'])
            ->get();
        foreach ($dat as $d) {
            $days = OfferDays::join('offer_specialties', 'offer_specialties.id', 'offer_days.id_offer')
                ->join('days', 'days.id', 'offer_days.id_day')
                ->select('days.name')
                ->where('id_offer', $d->id_offer)
                ->get();
            $days_vector = [];
            foreach ($days as $day)
                array_push($days_vector, $day->name);
            $index = [
                'id_offer' => $d->id_offer,
                'name' => $d->name,
                'last_name' => $d->last_name,
                'time_start' => $d->time_start,
                'time_end' => $d->time_end,
                'days' => $days_vector
            ];
            array_push($response, $index);
        }
        return response($response, 200);
    }

    public function getDaysAvailable(Request $request)
    {
        $data = $request->validate([
            'id_offer' => 'required|exists:offer_specialties,id',
        ]);
        $daysE = OfferDays::join('days', 'days.id', 'offer_days.id_day')
            ->select('days.id')
            ->where('offer_days.id_offer', $data['id_offer'])
            ->get();
        $days = [];
        foreach ($daysE as $d)
            array_push($days, $d->id);
        $dayNow = Carbon::now()->modify('+1 day')->dayOfWeek;
        if ($dayNow == 0) $dayNow = 7;
        $disponibles = [];
        for ($i = 0; $i < 7; $i++) {
            $day = (($dayNow + $i) % 7);
            $day = $day == 0 ? 7 : $day;
            if (in_array($day, $days)) {
                $k = $i + 1;
                $date = Carbon::now()->modify("+${k} day")
                    ->format('Y-m-d');
                array_push($disponibles, $date);
            }
        }
        return response($disponibles, 200);
    }
}
