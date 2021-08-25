<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Analysis;
use App\Models\Diagnostic;
use App\Models\Patient;
use App\Models\Treatment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

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
        $pag = 10;
        $patient = Patient::join('persons', 'persons.id', 'patients.id_person')
            ->select('persons.*', 'patients.id as id_patient', 'patients.blood_type', 'patients.allergy')
            ->where('patients.id', $patient->id)->first();
        $diagnostics = Diagnostic::join('consults', 'consults.id', 'diagnostics.id_consult')
            ->join('patients', 'patients.id', 'consults.id_patient')
            ->select('diagnostics.*', 'consults.id as id_consult', 'consults.time as time_consult')
            ->where('consults.id_patient', $patient->id_patient)
            ->paginate($pag, ['*'], 'diagnostics');
        $recetas = Treatment::join('prescriptions', 'prescriptions.id', 'treatments.id_prescription')
            ->join('consults', 'consults.id', 'prescriptions.id_consult')
            ->join('medicines', 'medicines.id', 'treatments.id_medicine')
            ->select('treatments.detail', 'medicines.*', 'prescriptions.time')
            ->where('consults.id_patient', $patient->id_patient)
            ->paginate($pag, ['*'], 'recetas');
        $analisis = Analysis::join('consults', 'consults.id', 'analysis.id_consult')
            ->where('consults.id_patient', $patient->id_patient)
            ->paginate($pag, ['*'], 'analisis');
        return view('doctor.historial.patient',
            compact('patient', 'diagnostics', 'recetas', 'analisis'));
    }

    public function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);

    }
}
