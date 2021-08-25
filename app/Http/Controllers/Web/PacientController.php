<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Analysis;
use App\Models\Diagnostic;
use App\Models\Patient;
use App\Models\Treatment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PacientController extends Controller
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

    public function show(Patient $pacient)
    {
        //
    }

    public function edit(Patient $pacient)
    {
        //
    }

    public function update(Request $request, Patient $pacient)
    {
        //
    }

    public function destroy(Patient $pacient)
    {
        //
    }

    public function historial(Patient $patient)
    {
        $patient = Patient::join('persons', 'persons.id', 'patients.id_person')
            ->select('persons.*', 'patients.id as id_patient', 'patients.blood_type', 'patients.allergy')
            ->where('patients.id', $patient->id)->first();
        $diagnostics = Diagnostic::join('consults', 'consults.id', 'diagnostics.id_consult')
            ->join('patients', 'patients.id', 'consults.id_patient')
            ->select('diagnostics.*', 'consults.id as id_consult', 'consults.time as time_consult')
            ->where('consults.id_patient', $patient->id_patient)->paginate(1);
        $recetas = [];
        $analisis = [];
        foreach ($diagnostics as $diag) {
            $rec = Treatment::join('prescriptions', 'prescriptions.id', 'treatments.id_prescription')
                ->join('consults', 'consults.id', 'prescriptions.id_consult')
                ->join('medicines', 'medicines.id', 'treatments.id_medicine')
                ->select('treatments.detail', 'medicines.*', 'prescriptions.time')
                ->where('prescriptions.id_consult', $diag->id_consult)->get();
            //dd($rec);
            $anl = Analysis::where('id_consult', $diag->id_consult)->get();
            array_push($recetas, $rec);
            array_push($analisis, $anl);
        }
        $now = Carbon::now();
        dd($now);
        return view('doctor.historial.patient',
            compact('patient', 'diagnostics', 'recetas', 'analisis'));
    }
}
