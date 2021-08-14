<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ActionReservation;
use App\Models\Consult;
use App\Models\Doctor;
use App\Models\NotificationDevice;
use App\Models\OfferSpecialty;
use App\Models\Patient;
use App\Models\Reservation;
use App\Notifications\ReservationNotify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    public function getByFilter(Request $request)
    {
        $data = $request->validate([
            'state' => 'required|string'
        ]);
        if ($data['state'] == 'todas') {
            $reservations = Reservation::join('patients', 'patients.id', 'reservations.id_patient')
                ->join('persons', 'persons.id', 'patients.id_person')
                ->join('offer_specialties', 'offer_specialties.id', 'reservations.id_offer')
                ->join('specialties', 'specialties.id', 'offer_specialties.id_specialty')
                ->select(
                    'reservations.id', 'specialties.name as name_specialty',
                    DB::raw("concat(persons.name,' ', persons.last_name) as name_complete"),
                    DB::raw("reservations.time_consult"),
                    'reservations.time_reservation',
                    'reservations.state'
                )
                ->orderby('time_reservation', 'asc')
                ->get();
        } else {
            $reservations = Reservation::join('patients', 'patients.id', 'reservations.id_patient')
                ->join('persons', 'persons.id', 'patients.id_person')
                ->join('offer_specialties', 'offer_specialties.id', 'reservations.id_offer')
                ->join('specialties', 'specialties.id', 'offer_specialties.id_specialty')
                ->select(
                    'reservations.id', 'specialties.name as name_specialty',
                    DB::raw("concat(persons.name,' ', persons.last_name) as name_complete"),
                    DB::raw("reservations.time_consult"),
                    'reservations.time_reservation',
                    'reservations.state'
                )
                ->where('state', $data['state'])
                ->orderby('time_reservation', 'asc')
                ->get();
        }
        return json_encode($reservations);
    }

    public function adminByFilter(Request $request)
    {
        $data = $request->validate([
            'state' => 'required|string'
        ]);
        if ($data['state'] == 'todas') {
            $reservations = Reservation::join('patients', 'patients.id', 'reservations.id_patient')
                ->join('offer_specialties', 'offer_specialties.id', 'reservations.id_offer')
                ->join('specialties', 'specialties.id', 'offer_specialties.id_specialty')
                ->join('doctors', 'doctors.id', 'offer_specialties.id_doctor')
                ->join('persons as p1', 'p1.id', 'patients.id_person')
                ->join('persons as p2', 'p2.id', 'doctors.id_person')
                ->select(
                    'reservations.id', 'specialties.name as name_specialty',
                    DB::raw("concat(p1.name,' ', p1.last_name) as name_patient"),
                    DB::raw("concat(p2.name,' ', p2.last_name) as name_doctor"),
                    DB::raw("reservations.time_consult"),
                    'reservations.time_reservation',
                    'reservations.state'
                )
                ->orderby('time_reservation', 'asc')
                ->get();
        }else{
            $reservations = Reservation::join('patients', 'patients.id', 'reservations.id_patient')
                ->join('offer_specialties', 'offer_specialties.id', 'reservations.id_offer')
                ->join('specialties', 'specialties.id', 'offer_specialties.id_specialty')
                ->join('doctors', 'doctors.id', 'offer_specialties.id_doctor')
                ->join('persons as p1', 'p1.id', 'patients.id_person')
                ->join('persons as p2', 'p2.id', 'doctors.id_person')
                ->select(
                    'reservations.id', 'specialties.name as name_specialty',
                    DB::raw("concat(p1.name,' ', p1.last_name) as name_patient"),
                    DB::raw("concat(p2.name,' ', p2.last_name) as name_doctor"),
                    DB::raw("reservations.time_consult"),
                    'reservations.time_reservation',
                    'reservations.state'
                )
                ->where('state', $data['state'])
                ->orderby('time_reservation', 'asc')
                ->get();
        }
        return json_encode($reservations);
    }

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
                'reservations.time_reservation',
                'reservations.state'
            )
            //->where('state', 'pendiente')
            //->orderby('state', 'desc')
            ->orderby('time_reservation', 'asc')
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
                    'body' => 'Reservacion aceptada'
                ],
                'data' => [
                    'message' => 'Su reservacion para ' . $res->time_consult . " ha sido aceptada\n" .
                        "Especialidad: " . $dat->name_specialty .
                        "\nDoctor: " . $dat->name_doctor
                ]
            ]);
        }
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
                    'body' => 'Reservacion rechazada'
                ],
                'data' => [
                    'message' => 'Su reservacion para ' . $res->time_consult . " ha sido cancelada\n" .
                        "Especialidad: " . $dat->name_specialty .
                        "\nDoctor: " . $dat->name_doctor .
                        "\nMotivo: " . $data['detail']
                ]
            ]);
        }
        return redirect()->route('reservations.index')->with(['gestion' => 'Reservacion rechazada']);
    }

    public function cancelReservation(Request $request){
        $data = $request->validate([
            'id_reservation' => 'required',
            'detail' => 'required'
        ]);
        $res = Reservation::find($data['id_reservation']);
        $res->state = 'cancelada';
        $res->save();
        $con = Consult::where('id_reservation', $res->id)->first();
        $con->state = 'cancelada';
        $con->save();
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
        return redirect()->route('reservations.index')->with(['gestion' => 'Reservacion cancelada']);
    }

    public function getPending(Request $request)
    {
        $pat = $request->user()->getPatient();
        $res = Reservation::join('patients', 'patients.id', 'reservations.id_patient')
            ->join('offer_specialties', 'offer_specialties.id', 'reservations.id_offer')
            ->join('specialties', 'specialties.id', 'offer_specialties.id_specialty')
            ->join('doctors', 'doctors.id', 'offer_specialties.id_doctor')
            ->join('persons', 'persons.id', 'doctors.id_person')
            ->select(
                'reservations.id as id_reservation',
                DB::raw("concat(persons . name, ' ', persons . last_name) as name_doctor"),
                'specialties.name as name_specialty',
                'reservations.time_consult'
            )
            ->where('reservations.time_consult', '>', Carbon::now())
            ->where('patients.id', $pat->id)
            ->where('reservations.state', 'pendiente')
            ->orderby('reservations.time_consult')
            ->get();
        return response($res, 200);
    }

    public function getPast(Request $request)
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
            )
            ->where('patients.id', $pat->id)
            ->where('consults.state', 'concluida')
            //->where('consults.time', '<', Carbon::now())
            ->orderby('consults.time')
            ->get();
        return response($con, 200);
    }

    public function byFilter(Request $request){
        $data = $request->validate([
            'filtro' => 'required|string'
        ]);
        if($data['filtro'] == 'todas'){
            $pat = $request->user()->getPatient();
            $res = Reservation::join('patients', 'patients.id', 'reservations.id_patient')
                ->join('offer_specialties', 'offer_specialties.id', 'reservations.id_offer')
                ->join('specialties', 'specialties.id', 'offer_specialties.id_specialty')
                ->join('doctors', 'doctors.id', 'offer_specialties.id_doctor')
                ->join('persons', 'persons.id', 'doctors.id_person')
                ->select(
                    'reservations.id as id_reservation',
                    DB::raw("concat(persons . name, ' ', persons . last_name) as name_doctor"),
                    'specialties.name as name_specialty',
                    'reservations.time_consult',
                    'reservations.state'
                )
                ->where('patients.id', $pat->id)
                ->orderby('reservations.time_consult', 'desc')
                ->get();
        }else {
            $pat = $request->user()->getPatient();
            $res = Reservation::join('patients', 'patients.id', 'reservations.id_patient')
                ->join('offer_specialties', 'offer_specialties.id', 'reservations.id_offer')
                ->join('specialties', 'specialties.id', 'offer_specialties.id_specialty')
                ->join('doctors', 'doctors.id', 'offer_specialties.id_doctor')
                ->join('persons', 'persons.id', 'doctors.id_person')
                ->select(
                    'reservations.id as id_reservation',
                    DB::raw("concat(persons . name, ' ', persons . last_name) as name_doctor"),
                    'specialties.name as name_specialty',
                    'reservations.time_consult',
                    'reservations.state'
                )
                ->where('patients.id', $pat->id)
                ->where('reservations.state', $data['filtro'])
                ->orderby('reservations.time_consult', 'desc')
                ->get();
        }
        return response($res, 200);
    }

    public function viewReservations(){
        $reservations = Reservation::join('patients', 'patients.id', 'reservations.id_patient')
            ->join('offer_specialties', 'offer_specialties.id', 'reservations.id_offer')
            ->join('specialties', 'specialties.id', 'offer_specialties.id_specialty')
            ->join('doctors', 'doctors.id', 'offer_specialties.id_doctor')
            ->join('persons as p1', 'p1.id', 'patients.id_person')
            ->join('persons as p2', 'p2.id', 'doctors.id_person')
            ->select(
                'reservations.id', 'specialties.name as name_specialty',
                DB::raw("concat(p1.name,' ', p1.last_name) as name_patient"),
                DB::raw("concat(p2.name,' ', p2.last_name) as name_doctor"),
                DB::raw("reservations.time_consult"),
                'reservations.time_reservation',
                'reservations.state'
            )
            ->orderby('time_reservation', 'asc')
            ->get();
        return view('admin.reservations', compact('reservations'));
    }

    public function test(Request $request)
    {
        $response = Http::withHeaders(
            ['Authorization' => 'key=AAAAvsZFQWs:APA91bEL2A-l2JFHhBGhafWqvGsXo12VEhgBYfx8BOhlQR3Z8NsWxFKETJW9ynbGpp41jozURY-GQnB6fANYZUye4_tF7XUpQZadjTFCm12NWnP0dAGyOI5O0YgY3hbrsLWWc5GaC3jd']
        )->post('https://fcm.googleapis.com/fcm/send?=', [
            'to' => 'ctNc7X3ITgmdJFqj3MSgRe:APA91bEiDtnaCcBAhOmW3P2qwYrSdAJLR1C86buYukMsnn0MBhv-MfRNfXLeIC90LzCKMJdpW2Q3WjgK4BY3b7tAjTmEUmSn3i0aFno5-S07D0Rq7QmIefPtOLNnFz5jtz6QgmEcZTtQ',
            'notification' => [
                'title' => 'Postman',
                'body' => 'Body desde postman'
            ],
            'data' => [
                'comida' => 'Comida desde postman'
            ]
        ]);
        return $response;
    }
}
