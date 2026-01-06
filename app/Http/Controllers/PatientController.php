<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Schedule;
use App\Models\Specialty;
use App\Models\AppointmentChangeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function checkPatientRole()
    {
        if (!Auth::check() || !Auth::user()->hasRole('patient')) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function dashboard()
    {
        $this->checkPatientRole();
        return view('patient.dashboard');
    }

    public function appointments()
    {
        $this->checkPatientRole();
        $patient = Auth::user()->patient;
        if (!$patient) {
            return redirect()->route('home')->with('error', 'Patient profile not found.');
        }
        $appointments = $patient->appointments()
            ->with('doctor')
            ->latest()
            ->paginate(10);
        return view('patient.appointments.index', compact('appointments'));
    }

    public function bookAppointment()
    {
        $this->checkPatientRole();
        $specialties = Specialty::with('doctors')->get();
        return view('patient.appointments.book', compact('specialties'));
    }

    public function getAvailableSlots(Request $request)
    {
        $this->checkPatientRole();
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after_or_equal:today'
        ]);

        $slots = Schedule::where('doctor_id', $request->doctor_id)
            ->where('date', $request->date)
            ->where('is_available', true)
            ->whereNotIn('id', function($query) use ($request) {
                $query->select('schedule_id')
                    ->from('appointments')
                    ->where('doctor_id', $request->doctor_id)
                    ->where('date', $request->date)
                    ->where('status', '!=', 'cancelled');
            })
            ->get();

        return response()->json($slots);
    }

    public function storeAppointment(Request $request)
    {
        $this->checkPatientRole();
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'schedule_id' => 'required|exists:schedules,id',
            'reason' => 'required|string'
        ]);

        $schedule = Schedule::findOrFail($request->schedule_id);
        
        if ($schedule->doctor_id != $request->doctor_id) {
            return back()->withErrors(['schedule_id' => 'Invalid schedule selected']);
        }

        if (!$schedule->is_available) {
            return back()->withErrors(['schedule_id' => 'This slot is no longer available']);
        }

        $appointment = Auth::user()->patient->appointments()->create([
            'doctor_id' => $request->doctor_id,
            'schedule_id' => $request->schedule_id,
            'appointment_date' => $schedule->date,
            'appointment_time' => $schedule->start_time,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        return redirect()->route('patient.appointments')
            ->with('success', 'Appointment booked successfully');
    }

    public function cancelAppointment(Appointment $appointment)
    {
        $this->checkPatientRole();
        if ($appointment->patient_id !== Auth::user()->patient->id) {
            abort(403);
        }

        if ($appointment->status === 'completed') {
            return back()->withErrors(['status' => 'Cannot cancel a completed appointment']);
        }

        $appointment->update(['status' => 'cancelled']);
        return back()->with('success', 'Appointment cancelled successfully');
    }

    public function medicalRecords()
    {
        $this->checkPatientRole();
        $patient = Auth::user()->patient;
        
        if (!$patient) {
            return redirect()->route('home')->with('error', 'Patient profile not found.');
        }

        $records = $patient->medicalRecords()
            ->with(['doctor', 'appointment'])
            ->latest()
            ->paginate(10);
            
        return view('patient.medical-records.index', ['medicalRecords' => $records]);
    }

    public function showMedicalRecord(MedicalRecord $record)
    {
        $this->checkPatientRole();
        if ($record->patient_id !== Auth::user()->patient->id) {
            abort(403);
        }

        $record->load(['doctor', 'appointment', 'treatments', 'prescriptions']);
        return view('patient.medical-records.show', compact('record'));
    }

    public function doctors()
    {
        $this->checkPatientRole();
        $doctors = Doctor::with(['user', 'specialties'])
            ->when(request('specialty'), function($query) {
                $query->whereHas('specialties', function($q) {
                    $q->where('specialties.id', request('specialty'));
                });
            })
            ->paginate(10);
        
        $specialties = Specialty::all();
        return view('patient.doctors.index', compact('doctors', 'specialties'));
    }

    public function showDoctor(Doctor $doctor)
    {
        $this->checkPatientRole();
        $doctor->load(['user', 'specialties', 'schedules' => function($query) {
            $query->where('date', '>=', now())
                ->where('is_available', true)
                ->orderBy('date')
                ->orderBy('start_time');
        }]);
        
        return view('patient.doctors.show', compact('doctor'));
    }

    public function profile()
    {
        $this->checkPatientRole();
        $patient = Auth::user()->patient;
        return view('patient.profile', compact('patient'));
    }

    public function updateProfile(Request $request)
    {
        $this->checkPatientRole();
        $patient = Auth::user()->patient;
        
        $request->validate([
            'phone' => 'required|string',
            'address' => 'required|string',
            'emergency_contact' => 'required|string',
            'medical_history' => 'nullable|string'
        ]);

        $patient->update($request->all());
        return back()->with('success', 'Profile updated successfully');
    }

    public function requestChange(Request $request)
    {
        $this->checkPatientRole();
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'change_type' => 'required|in:change,cancel',
            'message' => 'required|string|max:1000',
        ]);

        $appointment = Appointment::findOrFail($request->appointment_id);
        if ($appointment->patient_id !== auth()->id()) {
            return back()->with('error', 'You are not authorized to request changes for this appointment.');
        }

        AppointmentChangeRequest::create([
            'appointment_id' => $appointment->id,
            'patient_id' => auth()->id(),
            'type' => $request->change_type,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Your request has been submitted and is pending review.');
    }

    public function prescriptions()
    {
        $this->checkPatientRole();
        return view('patient.prescriptions');
    }

    public function bills()
    {
        $this->checkPatientRole();
        return view('patient.bills');
    }

    public function showRequestChangeForm($appointmentId)
    {
        $this->checkPatientRole();
        $appointment = \App\Models\Appointment::findOrFail($appointmentId);
        // Optionally, check if the appointment belongs to the patient
        if ($appointment->patient_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        return view('patient.appointments.request-change', compact('appointment'));
    }
} 