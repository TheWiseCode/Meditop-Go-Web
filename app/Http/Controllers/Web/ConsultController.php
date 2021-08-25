<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ActionReservation;
use App\Models\Analysis;
use App\Models\Consult;
use App\Models\Diagnostic;
use App\Models\Doctor;
use App\Models\Medicine;
use App\Models\NotificationDevice;
use App\Models\OfferSpecialty;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Reservation;
use App\Models\Treatment;
use App\Notifications\RecetaNotify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PDF;

class ConsultController extends Controller
{
    public function index()
    {
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
                'consults.time',
                'consults.state'
            )
            ->where('doctors.id', $doc->id)
            ->whereIn('consults.state', ['aceptada', 'proceso'])
            ->orderby('consults.time')
            ->get();
        $now = Carbon::now();
        $timeValidos = [];
        foreach ($consults as $con) {
            $antes = Carbon::createFromFormat('Y-m-d H:i:s', $con->time)->modify('-10 minutes');
            $va = $antes->lte($now);
            $despues = Carbon::createFromFormat('Y-m-d H:i:s', $con->time)->modify('+30 minutes');
            $vd = $despues->gte($now);
            $valido = $va && $vd;
            array_push($timeValidos, $valido);
        }
        return view('doctor.consults.index', compact('consults', 'now', 'timeValidos'));
    }

    public
    function create()
    {
        //
    }

    public
    function store(Request $request)
    {
        //dd($request->request);
        /*$data = $request->validate([
            'id_consult' => 'required',
            'diagnostic' => 'required',
            'receta' => 'required',
            'analisis' => 'required',
            'detail_diagnostic' => 'required',
            'detail_analisis' => 'string',
            'detail_receta' => 'array',
            'medicamento' => 'array',
            'generico' => 'array',
            'dosis' => 'array',
            'concentracion' => 'array',
        ]);*/
        //dd($data);
        $con = Consult::find($request->input('id_consult'));
        $con->state = 'concluida';
        $con->save();
        $diag = Diagnostic::create([
            'id_consult' => $request->input('id_consult'),
            'detail' => $request->input('detail_diagnostic'),
            'time' => Carbon::now()
        ]);
        if ($request->input('analisis') == 1) {
            $anl = Analysis::create([
                'id_consult' => $request->input('id_consult'),
                'detail' => $request->input('detail_analisis'),
                //'time' => Carbon::now()
            ]);
        }
        if ($request->input('receta') == 1) {
            $c = count($request->input('medicamento'));
            $pres = Prescription::create([
                'id_consult' => $request->input('id_consult'),
                'time' => Carbon::now()
            ]);
            for ($i = 0; $i < $c; $i++) {
                $med = Medicine::where('name', $request->input('medicamento')[$i])
                    ->where('name_generic', $request->input('generico')[$i])
                    ->where('dose', $request->input('dosis')[$i])
                    ->where('concentration', $request->input('concentracion')[$i])->first();
                if ($med == null) {
                    $med = Medicine::create([
                        'name' => $request->input('medicamento')[$i],
                        'name_generic' => $request->input('generico')[$i],
                        'dose' => $request->input('dosis')[$i],
                        'concentration' => $request->input('concentracion')[$i]
                    ]);
                }
                Treatment::create([
                    'id_medicine' => $med->id,
                    'id_prescription' => $pres->id,
                    'detail' => $request->input('detail_receta')[$i]
                ]);
            }
            $doctor = Doctor::getPerson($con->id_doctor);
            $receta = Treatment::join('prescriptions', 'prescriptions.id', 'treatments.id_prescription')
                ->join('consults', 'consults.id', 'prescriptions.id_consult')
                ->join('medicines', 'medicines.id', 'treatments.id_medicine')
                ->select('treatments.detail', 'medicines.*', 'prescriptions.time')
                ->where('prescriptions.id_consult', $con->id)->get();
            $pdf = PDF::loadView('doctor.receta.print', compact('receta', 'doctor'))->setOptions(['defaultFont' => 'sans-serif']);
            $file = $pdf->setPaper('a4')->output();
            $user = Patient::getUser($con->id_patient);
            $user->notify(new RecetaNotify($file));
        }
        return redirect()->route('consults.index')->with(['gestion' => 'Consulta finalizada']);
    }

    public
    function startConsult(Request $request, Consult $consult)
    {
        if ($consult->state == 'aceptada' || $consult->state == 'proceso') {
            $consult->state = 'proceso';
            $consult->save();
            $link = $consult->url_jitsi;
            $room = substr($link, strlen("https://meet.jit.si/"));
            //dd($room, $link);
            $consult = Consult::join('doctors', 'doctors.id', 'consults.id_doctor')
                ->join('reservations', 'reservations.id', 'consults.id_reservation')
                ->join('offer_specialties', 'offer_specialties.id', 'reservations.id_offer')
                ->join('specialties', 'specialties.id', 'offer_specialties.id_specialty')
                ->join('persons', 'persons.id', 'doctors.id_person')
                ->select(
                    DB::raw("concat(persons.name, ' ', persons.last_name) as name_patient"),
                    'specialties.name as name_specialty',
                    'persons.email as email_patient',
                    'consults.*'
                )
                ->where('consults.id', $consult->id)
                ->first();
            /*$doctor = Doctor::getPerson($consult->id_doctor);
            $receta = Treatment::join('prescriptions', 'prescriptions.id', 'treatments.id_prescription')
                ->join('consults', 'consults.id', 'prescriptions.id_consult')
                ->join('medicines', 'medicines.id', 'treatments.id_medicine')
                ->select('treatments.detail', 'medicines.*', 'prescriptions.time')
                ->where('prescriptions.id_consult', $consult->id)->get();
            $pdf = PDF::loadView('doctor.receta.print', compact('receta', 'doctor'))->setOptions(['defaultFont' => 'sans-serif']);
            $file = $pdf->setPaper('a4')->output();
            $user = Patient::getUser($consult->id_patient);
            $user->notify(new RecetaNotify($file));
            dd($user);*/
            return view('doctor.consults.show', compact('consult', 'room'));
        }
    }

    public
    function show(Consult $consult)
    {
        if ($consult->state == 'aceptada' || $consult->state == 'proceso') {
            $link = $consult->url_jitsi;
            $room = substr($link, strlen("https://meet.jit.si/"));
            $consult = Consult::join('patients', 'patients.id', 'consults.id_patient')
                ->join('reservations', 'reservations.id', 'consults.id_reservation')
                ->join('offer_specialties', 'offer_specialties.id', 'reservations.id_offer')
                ->join('specialties', 'specialties.id', 'offer_specialties.id_specialty')
                ->join('persons', 'persons.id', 'patients.id_person')
                ->select(
                    DB::raw("concat(persons.name, ' ', persons.last_name) as name_patient"),
                    'specialties.name as name_specialty',
                    'persons.email as email_patient',
                    'consults.*'
                )
                ->where('consults.id', $consult->id)
                ->first();
            return view('doctor.consults.show', compact('consult', 'room'));
        }
    }

    public
    function edit(Consult $consult)
    {
        //
    }

    public
    function update(Request $request, Consult $consult)
    {
        //
    }

    public
    function destroy(Consult $consult)
    {
        //
    }

    public
    function cancelConsult(Request $request)
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

    public
    function consultIn(Request $request)
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

    public
    function getScheduled(Request $request)
    {
        $pat = $request->user()->getPatient();
        $con = Consult::join('patients', 'patients.id', 'consults.id_patient')
            ->join('reservations', 'reservations.id', 'consults.id_reservation')
            ->join('offer_specialties', 'offer_specialties.id', 'reservations.id_offer')
            ->join('specialties', 'specialties.id', 'offer_specialties.id_specialty')
            ->join('doctors', 'doctors.id', 'consults.id_doctor')
            ->join('persons', 'persons.id', 'doctors.id_person')
            ->select(
                'patients.id as id_patient',
                'consults.id as id_consult',
                DB::raw("concat(persons . name, ' ', persons . last_name) as name_doctor"),
                'specialties.name as name_specialty',
                'consults.time',
                'consults.url_jitsi'
            )
            ->where('patients.id', $pat->id)
            /*->where(function ($query) {
                $query->where('consults.state', 'aceptada')
                    ->or_where('consults.state', 'proceso');
            })*/
            ->whereIn('consults.state', ['aceptada', 'proceso'])
            ->where('consults.time', '>', Carbon::now())
            ->orderby('consults.time')
            ->get();
        return response($con, 200);
    }
}
