<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ActionReservation;
use App\Models\Consult;
use App\Models\NotificationDevice;
use App\Models\OfferSpecialty;
use App\Models\Patient;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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
        $dat = OfferSpecialty::join('specialties', 'specialties.id', 'offer_specialties.id_specialty')
            ->join('reservations', 'reservations.id_offer', 'offer_specialties.id')
            ->join('doctors', 'doctors.id', 'offer_specialties.id_doctor')
            ->join('persons', 'persons.id', 'doctors.id_person')
            ->select(
                DB::raw("concat(persons.name,' ',persons.last_name) as name_doctor"),
                'specialties.name as name_specialty')
            ->where('reservations.id', $res->id)->first();
        $user = Patient::getUser($res->id_patient);
        $devices = NotificationDevice::where('id_user', $user->id)->get();
        foreach ($devices as $dev) {
            $response = Http::withHeaders(
                ['Authorization' => 'key=AAAAvsZFQWs:APA91bEL2A-l2JFHhBGhafWqvGsXo12VEhgBYfx8BOhlQR3Z8NsWxFKETJW9ynbGpp41jozURY-GQnB6fANYZUye4_tF7XUpQZadjTFCm12NWnP0dAGyOI5O0YgY3hbrsLWWc5GaC3jd']
            )->post('https://fcm.googleapis.com/fcm/send?=', [
                'to' => $dev->token,
                'notification' => [
                    'title' => 'Meditop Go',
                    'body' => 'Consulta cancelada'
                ],
                'data' => [
                    'message' => 'Su consulta para ' . $con->time . " ha sido cancelada\n" .
                        "Especialidad: " . $dat->name_specialty .
                        "\nDoctor: " . $dat->name_doctor .
                        "\nMotivo: " . $data['detail']
                ]
            ]);
        }
        return redirect()->route('consults.index')->with(['gestion' => 'Consulta cancelada']);
    }

    public function consultIn(Request $request)
    {
        $data = $request->validate([
            'id_consult' => 'required'
        ]);
        $con = Consult::find($data['id_consult']);
        if ($con->state == 'aceptada') {
            return response(['message' => 'Consulta todavia no iniciada'], 201);
        }
        if ($con->state == 'proceso') {
            return response(['message' => 'Consulta en proceso'], 200);
        }
    }

    public function getScheduled(Request $request)
    {
        $pat = $request->user()->getPatient();
        $con = Consult::join('patients', 'patients.id', 'consults.id_patient')
            ->join('reservations', 'reservations.id', 'consults.id_reservation')
            ->join('offer_specialties', 'offer_specialties.id', 'reservations.id_offer')
            ->join('specialties', 'specialties.id', 'offer_specialties.id_specialty')
            ->join('doctors', 'doctors.id', 'consults.id_doctor')
            ->join('persons', 'persons.id', 'doctors.id_person')
            ->select(
                'consults.id as id_consult',
                DB::raw("concat(persons . name, ' ', persons . last_name) as name_doctor"),
                'specialties.name as name_specialty',
                'consults.time',
                'consults.url_jitsi'
            )
            ->where('patients.id', $pat->id)
            ->where(function ($query) {
                $query->where('consults.state', 'aceptada');
                $query->or_where('consults.state', 'proceso');
            })
            //->where('consults.state', 'in', ['aceptada', 'proceso'])
            ->where('consults.time', '>', Carbon::now())
            ->orderby('consults.time')
            ->get();
        return response($con, 200);
    }
}
