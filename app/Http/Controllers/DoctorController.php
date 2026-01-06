<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Schedule;
use App\Models\HealthTip;
use App\Models\Specialty;
use App\Models\AppointmentChangeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('doctor');
    }

    public function appointments()
    {
        $doctor = Auth::user()->doctor;
        $appointments = $doctor->appointments()
            ->with('patient')
            ->latest()
            ->paginate(10);
        return view('doctor.appointments.index', compact('appointments'));
    }

    public function schedule()
    {
        $doctor = Auth::user()->doctor;
        $schedules = $doctor->schedules()
            ->where('date', '>=', now())
            ->orderBy('date')
            ->orderBy('start_time')
            ->paginate(10);
        return view('doctor.schedule.index', compact('schedules'));
    }

    public function createSchedule()
    {
        return view('doctor.schedule.create');
    }

    public function storeSchedule(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_patients' => 'required|integer|min:1',
            'is_available' => 'boolean'
        ]);

        Auth::user()->doctor->schedules()->create($request->all());
        return redirect()->route('doctor.schedule')->with('success', 'Schedule created successfully');
    }

    public function medicalRecords()
    {
        $doctor = Auth::user()->doctor;
        $records = $doctor->medicalRecords()
            ->with(['patient', 'appointment'])
            ->latest()
            ->paginate(10);
        return view('doctor.medical-records.index', compact('records'));
    }

    public function createMedicalRecord(Request $request, Appointment $appointment)
    {
        if ($appointment->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }
        return view('doctor.medical-records.create', compact('appointment'));
    }

    public function storeMedicalRecord(Request $request, Appointment $appointment)
    {
        if ($appointment->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        $request->validate([
            'diagnosis' => 'required|string',
            'treatment_notes' => 'required|string',
            'prescription' => 'required|string',
            'follow_up_date' => 'nullable|date|after:today'
        ]);

        $record = $appointment->medicalRecord()->create([
            'patient_id' => $appointment->patient_id,
            'doctor_id' => Auth::user()->doctor->id,
            'diagnosis' => $request->diagnosis,
            'treatment_notes' => $request->treatment_notes,
            'prescription' => $request->prescription,
            'follow_up_date' => $request->follow_up_date
        ]);

        return redirect()->route('doctor.medical-records.show', $record)
            ->with('success', 'Medical record created successfully');
    }

    public function healthTips()
    {
        $doctor = Auth::user()->doctor;
        $tips = $doctor->healthTips()->latest()->paginate(10);
        return view('doctor.health-tips.index', compact('tips'));
    }

    public function createHealthTip()
    {
        return view('doctor.health-tips.create');
    }

    public function storeHealthTip(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'is_published' => 'boolean'
        ]);

        Auth::user()->doctor->healthTips()->create($request->all());
        return redirect()->route('doctor.health-tips')->with('success', 'Health tip created successfully');
    }

    public function profile()
    {
        $doctor = Auth::user()->doctor;
        $specialties = Specialty::all();
        return view('doctor.profile', compact('doctor', 'specialties'));
    }

    public function updateProfile(Request $request)
    {
        $doctor = Auth::user()->doctor;
        
        $request->validate([
            'qualifications' => 'required|string',
            'experience' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'specialties' => 'required|array'
        ]);

        $doctor->update($request->except('specialties'));
        $doctor->specialties()->sync($request->specialties);

        return back()->with('success', 'Profile updated successfully');
    }

    public function changeRequests()
    {
        $doctor = auth()->user()->doctor;
        $requests = AppointmentChangeRequest::whereHas('appointment', function($q) use ($doctor) {
            $q->where('doctor_id', $doctor->id);
        })->with(['appointment.patient', 'patient'])->latest()->paginate(20);
        return view('doctor.change-requests', compact('requests'));
    }

    public function processChangeRequest(Request $request, AppointmentChangeRequest $changeRequest)
    {
        $doctor = auth()->user()->doctor;
        // Ensure the doctor owns the appointment
        if ($changeRequest->appointment->doctor_id !== $doctor->id) {
            abort(403);
        }
        $request->validate(['action' => 'required|in:approved,rejected']);

        $changeRequest->status = $request->action;
        $changeRequest->save();

        if ($changeRequest->type === 'cancel' && $request->action === 'approved') {
            $changeRequest->appointment->update(['status' => 'cancelled']);
        }

        return back()->with('success', 'Request processed.');
    }

    public function select($specialty)
    {
        // Logic to select doctors based on specialty
        $doctors = Doctor::whereHas('specialties', function($query) use ($specialty) {
            $query->where('name', $specialty);
        })->get();
        return view('doctors.select', compact('doctors', 'specialty'));
    }
} 