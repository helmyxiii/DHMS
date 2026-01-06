<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('doctor')) {
            // For doctors: show their appointments with patients
            $appointments = $user->doctor->appointments()
                ->with('patient')
                ->orderBy('date')
                ->paginate(10);
            return view('appointments.index', compact('appointments'));
        }

        // For patients: show their own upcoming appointments
        $appointments = Appointment::where('patient_id', $user->id)
            ->whereDate('appointment_date', '>=', now()->toDateString())
            ->orderBy('appointment_date')
            ->get();
        return view('appointments.index', compact('appointments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|string',
            'reason' => 'required|string|max:500',
        ]);

        Appointment::create([
            'doctor_id' => $request->doctor_id,
            'patient_id' => auth()->id(),
            'appointment_date' => $request->appointment_date,
            'time_slot' => $request->time_slot,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('appointments.index')->with('success', 'Appointment booked successfully.');
    }

    public function cancel(Appointment $appointment)
    {
        if ($appointment->patient_id !== auth()->id() || $appointment->status !== 'pending') {
            return back()->with('error', 'You are not authorized to cancel this appointment.');
        }

        $appointment->status = 'cancelled';
        $appointment->save();

        return back()->with('success', 'Appointment cancelled successfully.');
    }

    public function show(Appointment $appointment)
    {
        // Load relationships for the appointment
        $appointment->load(['doctor', 'patient']);

        // Check if the user is authorized to view this appointment
        if (!auth()->user()->isAdmin() && 
            auth()->id() !== $appointment->doctor_id && 
            auth()->id() !== $appointment->patient_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('appointments.show', compact('appointment'));
    }

    public function create(Request $request)
    {
        $doctor = $request->input('doctor', 'Unknown Doctor');
        $doctors = User::where('role', 'doctor')->get();
        return view('appointments.create', compact('doctor', 'doctors'));
    }

    public function selectDoctor($specialty)
    {
        $doctors = User::where('role', 'doctor')->where('specialty', $specialty)->get();
        return view('appointments.select_doctor', compact('doctors', 'specialty'));
    }

    public function createForDoctor($doctorId)
    {
        $doctor = User::findOrFail($doctorId);
        return view('appointments.create', compact('doctor'));
    }
}
