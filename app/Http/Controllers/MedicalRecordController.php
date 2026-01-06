<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Treatment;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $records = null;
        $doctors = null;
        $patients = null;

        if ($user->isAdmin()) {
            $doctors = \App\Models\Doctor::with('user')->get();
            $patients = \App\Models\Patient::with('user')->get();
            $records = MedicalRecord::with(['patient', 'doctor', 'appointment'])
                ->when($request->date, function ($query, $date) {
                    $query->whereDate('record_date', $date);
                })
                ->when($request->doctor_id, function ($query, $doctorId) {
                    $query->where('doctor_id', $doctorId);
                })
                ->when($request->patient_id, function ($query, $patientId) {
                    $query->where('patient_id', $patientId);
                })
                ->latest()
                ->paginate(10)
                ->appends($request->all());
        } elseif ($user->isDoctor()) {
            $patients = \App\Models\Patient::with('user')->get();
            $records = $user->doctor->medicalRecords()
                ->with(['patient', 'appointment'])
                ->when($request->date, function ($query, $date) {
                    $query->whereDate('record_date', $date);
                })
                ->when($request->patient_id, function ($query, $patientId) {
                    $query->where('patient_id', $patientId);
                })
                ->latest()
                ->paginate(10)
                ->appends($request->all());
        } elseif ($user->isPatient()) {
            $records = $user->patient->medicalRecords()
                ->with(['doctor', 'appointment'])
                ->when($request->date, function ($query, $date) {
                    $query->whereDate('record_date', $date);
                })
                ->latest()
                ->paginate(10)
                ->appends($request->all());
        }

        // Ensure we always have a paginated collection
        if (!$records) {
            $records = MedicalRecord::query()->paginate(10);
        }

        return view('medical-records.index', [
            'medicalRecords' => $records,
            'doctors' => $doctors,
            'patients' => $patients,
        ]);
    }

    public function show(MedicalRecord $medicalRecord)
    {
        $user = Auth::user();
        
        if ($user->isAdmin() || 
            ($user->isDoctor() && $medicalRecord->doctor_id === $user->doctor->id) ||
            ($user->isPatient() && $medicalRecord->patient_id === $user->patient->id)) {
            
            $medicalRecord->load(['patient', 'doctor', 'appointment', 'treatments', 'prescriptions']);
            return view('medical-records.show', ['medicalRecord' => $medicalRecord]);
        }

        abort(403);
    }

    public function addTreatment(Request $request, MedicalRecord $record)
    {
        if (!Auth::user()->isDoctor() || $record->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        $request->validate([
            'treatment_name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:planned,in_progress,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        $record->treatments()->create($request->all());
        return back()->with('success', 'Treatment added successfully');
    }

    public function addPrescription(Request $request, MedicalRecord $record)
    {
        if (!Auth::user()->isDoctor() || $record->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        $request->validate([
            'medicine_name' => 'required|string|max:255',
            'dosage' => 'required|string',
            'frequency' => 'required|string',
            'duration' => 'required|string',
            'instructions' => 'required|string',
            'prescribed_date' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:prescribed_date'
        ]);

        $record->prescriptions()->create($request->all());
        return back()->with('success', 'Prescription added successfully');
    }

    public function updateTreatment(Request $request, MedicalRecord $record, Treatment $treatment)
    {
        if (!Auth::user()->isDoctor() || $record->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        $request->validate([
            'treatment_name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:planned,in_progress,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        $treatment->update($request->all());
        return back()->with('success', 'Treatment updated successfully');
    }

    public function updatePrescription(Request $request, MedicalRecord $record, Prescription $prescription)
    {
        if (!Auth::user()->isDoctor() || $record->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        $request->validate([
            'medicine_name' => 'required|string|max:255',
            'dosage' => 'required|string',
            'frequency' => 'required|string',
            'duration' => 'required|string',
            'instructions' => 'required|string',
            'prescribed_date' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:prescribed_date'
        ]);

        $prescription->update($request->all());
        return back()->with('success', 'Prescription updated successfully');
    }

    public function deleteTreatment(MedicalRecord $record, Treatment $treatment)
    {
        if (!Auth::user()->isDoctor() || $record->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        $treatment->delete();
        return back()->with('success', 'Treatment deleted successfully');
    }

    public function deletePrescription(MedicalRecord $record, Prescription $prescription)
    {
        if (!Auth::user()->isDoctor() || $record->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        $prescription->delete();
        return back()->with('success', 'Prescription deleted successfully');
    }

    public function destroy(MedicalRecord $medicalRecord)
    {
        $medicalRecord->delete();
        return redirect()->route('admin.medical-records.index')->with('success', 'Medical record deleted successfully.');
    }

    public function create()
    {
        $user = Auth::user();
        $doctors = null;
        $patients = null;
        if ($user->isAdmin()) {
            $doctors = \App\Models\Doctor::with('user')->get();
            $patients = \App\Models\Patient::with('user')->get();
        } elseif ($user->isDoctor()) {
            $patients = \App\Models\Patient::with('user')->get();
        }
        return view('medical-records.create', [
            'doctors' => $doctors,
            'patients' => $patients,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'patient_id' => 'required|exists:patients,id',
            'symptoms' => 'required|string',
            'diagnosis' => 'required|string',
            'notes' => 'nullable|string',
            'record_type' => 'required|string',
            'record_date' => 'required|date',
        ]);

        $medicalRecord = MedicalRecord::create([
            'doctor_id' => $validated['doctor_id'],
            'patient_id' => $validated['patient_id'],
            'symptoms' => $validated['symptoms'],
            'diagnosis' => $validated['diagnosis'],
            'notes' => $validated['notes'] ?? null,
            'record_type' => $validated['record_type'],
            'record_date' => $validated['record_date'],
        ]);

        // Save treatments if any
        if ($request->has('treatments')) {
            foreach ($request->input('treatments') as $treatment) {
                $medicalRecord->treatments()->create($treatment);
            }
        }

        // Save prescriptions if any
        if ($request->has('prescriptions')) {
            foreach ($request->input('prescriptions') as $prescription) {
                $medicalRecord->prescriptions()->create($prescription);
            }
        }

        // Redirect to index so the view always gets a paginator
        return redirect()->route('patient.medical-records.index')->with('success', 'Medical record created successfully.');
    }

    public function edit(MedicalRecord $medicalRecord)
    {
        $user = Auth::user();
        $doctors = null;
        $patients = null;

        if ($user->isAdmin()) {
            $doctors = \App\Models\Doctor::with('user')->get();
            $patients = \App\Models\Patient::with('user')->get();
        } elseif ($user->isDoctor()) {
            $patients = \App\Models\Patient::with('user')->get();
        }

        // Optionally, check permissions here

        return view('medical-records.edit', [
            'medicalRecord' => $medicalRecord,
            'doctors' => $doctors,
            'patients' => $patients,
        ]);
    }

    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        $validated = $request->validate([
            'symptoms' => 'required|string',
            'diagnosis' => 'required|string',
            'notes' => 'nullable|string',
            // Add other fields as needed
        ]);

        $medicalRecord->update($validated);

        // Handle deleted treatments
        $treatmentIds = collect($request->input('treatments', []))
            ->pluck('id')
            ->filter()
            ->all();
        $medicalRecord->treatments()
            ->whereNotIn('id', $treatmentIds)
            ->delete();

        // Update or create treatments
        if ($request->has('treatments')) {
            foreach ($request->input('treatments') as $treatmentData) {
                if (isset($treatmentData['id'])) {
                    $treatment = $medicalRecord->treatments()->find($treatmentData['id']);
                    if ($treatment) {
                        $treatment->update($treatmentData);
                    }
                } else {
                    $medicalRecord->treatments()->create($treatmentData);
                }
            }
        }

        // Handle deleted prescriptions
        $prescriptionIds = collect($request->input('prescriptions', []))
            ->pluck('id')
            ->filter()
            ->all();
        $medicalRecord->prescriptions()
            ->whereNotIn('id', $prescriptionIds)
            ->delete();

        // Update or create prescriptions
        if ($request->has('prescriptions')) {
            foreach ($request->input('prescriptions') as $prescriptionData) {
                if (isset($prescriptionData['id'])) {
                    $prescription = $medicalRecord->prescriptions()->find($prescriptionData['id']);
                    if ($prescription) {
                        $prescription->update($prescriptionData);
                    }
                } else {
                    $medicalRecord->prescriptions()->create($prescriptionData);
                }
            }
        }

        // Redirect to index so the view always gets a paginator
        return redirect()->route('patient.medical-records.index')->with('success', 'Medical record updated successfully.');
    }
} 