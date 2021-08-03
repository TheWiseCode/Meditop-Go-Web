<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Day;
use App\Models\Doctor;
use App\Models\OfferDays;
use App\Models\OfferSpecialty;
use App\Models\Specialty;
use Illuminate\Http\Request;

class DoctorController extends Controller
{

    public function validar()
    {
        if (!auth()->user()->isDoctor()) {
            abort(401);
        }
    }

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


    public function show(Doctor $doctor)
    {
        //
    }

    public function edit(Doctor $doctor)
    {
        //
    }

    public function update(Request $request, Doctor $doctor)
    {
        //
    }

    public function destroy(Doctor $doctor)
    {
        //
    }

    public function schedule()
    {
        $this->validar();
        $doc = auth()->user()->getDoctor();
        $offers = OfferSpecialty::join('specialties', 'specialties.id', 'offer_specialties.id_specialty')
            ->select('offer_specialties.id', 'time_start', 'time_end', 'name')
            ->where('id_doctor', $doc->id)->get();
        $schedules = [];
        foreach ($offers as $offer) {
            $sch = OfferDays::join('days', 'days.id', 'offer_days.id_day')
                ->select('offer_days.id_day', 'days.name')
                ->where('offer_days.id_offer', $offer->id)->get();
            array_push($schedules, $sch);
        }
        return view('doctor.schedule.index', compact('offers', 'schedules'));
    }

    public function addSchedule()
    {
        $this->validar();
        $specialties = Specialty::all();
        $days = Day::all();
        return view('doctor.schedule.add', compact('specialties', 'days'));
    }

    public function getHourParsed($time)
    {
        $this->validar();
        $am = substr($time, strlen($time) - 2, 2);
        $time = substr($time, 0, strlen($time) - 2);
        $parts = explode(':', $time);
        $parts = array_map(function ($x) {
            return intval($x);
        }, $parts);
        if ($am == 'PM' && $parts[0] != 12) {
            $parts[0] += 12;
        }
        return '' . sprintf("%'.02d", $parts[0]) . ':' . sprintf("%'.02d", $parts[1]);
    }

    public function registerSchedule(Request $request)
    {
        $this->validar();
        $data = $request->validate([
            'specialty' => 'required',
            'days' => 'required|array|min:1',
            'time-start' => 'required|string',
            'time-end' => 'required|string',
        ]);
        $doc = auth()->user()->getDoctor();
        $start = $this->getHourParsed($data['time-start']);
        $end = $this->getHourParsed($data['time-end']);
        $off = OfferSpecialty::create([
            'id_specialty' => $data['specialty'],
            'id_doctor' => $doc->id,
            'time_start' => $start,
            'time_end' => $end,
        ]);
        foreach ($data['days'] as $day) {
            OfferDays::create([
                'id_offer' => $off->id,
                'id_day' => $day
            ]);
        }
        return redirect()->route('doctor-schedule')->with(['gestion' => 'Nuevo horario registrado']);
        //dd($start, $end);
    }

    public function editSchedule(OfferSpecialty $offer)
    {
        $this->validar();
        $specialties = Specialty::all();
        $days = Day::all();
        return view('doctor.schedule.edit', compact('offer', 'specialties', 'days'));
    }

    public function updateSchedule(Request $request, OfferSpecialty $offer)
    {
        $this->validar();
        $data = $request->validate([
            'specialty' => 'required',
            'days' => 'required|array|min:1',
            'time-start' => 'required|string',
            'time-end' => 'required|string',
        ]);
        $doc = auth()->user()->getDoctor();
        $start = $this->getHourParsed($data['time-start']);
        $end = $this->getHourParsed($data['time-end']);
        $offer->update([
            'id_specialty' => $data['specialty'],
            'id_doctor' => $doc->id,
            'time_start' => $start,
            'time_end' => $end,
        ]);
        OfferDays::where('id_offer', $offer->id)->delete();
        foreach ($data['days'] as $day) {
            OfferDays::create([
                'id_offer' => $offer->id,
                'id_day' => $day
            ]);
        }
        return redirect()->route('doctor-schedule')->with(['gestion' => 'Horario modificado']);
    }

    public function deleteSchedule(OfferSpecialty $offer)
    {
        $this->validar();
        OfferDays::where('id_offer', $offer->id)->delete();
        $offer->delete();
        return redirect()->route('doctor-schedule')->with(['gestion' => 'Horario eliminado']);
    }
}
