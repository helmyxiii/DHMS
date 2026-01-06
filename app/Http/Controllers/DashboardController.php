<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Schedule;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $role = $user->role;
        $data = [];

        switch ($role) {
            case 'admin':
                $data = $this->getAdminDashboardData();
                break;
            case 'doctor':
                $data = $this->getDoctorDashboardData($user->doctor);
                break;
            case 'patient':
                $data = $this->getPatientDashboardData($user);
                $data['announcements'] = Announcement::latest()->take(5)->get();
                break;
            default:
                return redirect()->route('login');
        }
        $data['role'] = $role;
        return view('dashboard', $data);
    }

    private function getAdminDashboardData()
    {
        return [
            'totalDoctors' => Doctor::count(),
            'totalPatients' => Patient::count(),
            'totalAppointments' => Appointment::count(),
            'totalMedicalRecords' => MedicalRecord::count(),
            'recentAppointments' => Appointment::with(['doctor', 'patient'])
                ->latest()
                ->take(5)
                ->get(),
            'upcomingAppointments' => Appointment::with(['doctor', 'patient'])
                ->where('appointment_date', '>', now())
                ->orderBy('appointment_date')
                ->take(5)
                ->get(),
        ];
    }

    private function getDoctorDashboardData($doctor)
    {
        if (!$doctor) {
            abort(403, 'No doctor profile found for this user.');
        }
        return [
            'doctor' => $doctor,
            'totalPatients' => $doctor->patients()->count(),
            'totalAppointments' => $doctor->appointments()->count(),
            'totalMedicalRecords' => $doctor->medicalRecords()->count(),
            'todayAppointments' => $doctor->appointments()
                ->whereDate('appointment_date', today())
                ->orderBy('appointment_date')
                ->paginate(10),
            'upcomingAppointments' => $doctor->appointments()
                ->where('appointment_date', '>', now())
                ->orderBy('appointment_date')
                ->take(5)
                ->get(),
            'recentMedicalRecords' => $doctor->medicalRecords()
                ->with(['patient', 'prescriptions', 'treatments'])
                ->latest()
                ->take(5)
                ->get(),
            'pendingRecords' => 0,
            'availableSlots' => $doctor->schedules()
                ->where('is_available', true)
                ->where('date', '>=', now()->toDateString())
                ->count(),
            'recentAppointments' => $doctor->appointments()
                ->with('patient')
                ->latest()
                ->paginate(10),
            'todaySchedule' => $doctor->schedules()
                ->where('date', today())
                ->orderBy('start_time')
                ->paginate(10),
        ];
    }

    private function getPatientDashboardData($user)
    {
        $recentAppointments = $user->appointments()
            ->latest()
            ->take(5)
            ->get()
            ->map(function($appointment) {
                return (object)[
                    'created_at' => $appointment->created_at,
                    'description' => 'Appointment with Dr. ' . ($appointment->doctor->user->name ?? 'Unknown'),
                    'status' => ucfirst($appointment->status),
                    'status_color' => $appointment->status === 'completed' ? 'success' : ($appointment->status === 'cancelled' ? 'danger' : 'warning'),
                    'action_url' => route('patient.appointments.index'),
                ];
            });

        $recentMedicalRecords = $user->medicalRecords()
            ->with(['doctor', 'prescriptions', 'treatments'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function($record) {
                return (object)[
                    'created_at' => $record->created_at,
                    'description' => 'Medical record updated',
                    'status' => 'Record',
                    'status_color' => 'info',
                    'action_url' => route('patient.medical-records.show', $record->id),
                ];
            });

        $recentActivities = $recentAppointments->merge($recentMedicalRecords)->sortByDesc('created_at')->take(5);

        return [
            'patient' => $user,
            'totalAppointments' => $user->appointments()->count(),
            'totalMedicalRecords' => $user->medicalRecords()->count(),
            'upcomingAppointments' => $user->appointments()
                ->where('appointment_date', '>', now())
                ->orderBy('appointment_date')
                ->take(5)
                ->get(),
            'recentMedicalRecords' => $user->medicalRecords()
                ->with(['doctor', 'prescriptions', 'treatments'])
                ->latest()
                ->take(5)
                ->get(),
            'activeTreatments' => $user->medicalRecords()
                ->with(['treatments' => function ($query) {
                    $query->where('status', 'ongoing');
                }])
                ->get()
                ->pluck('treatments')
                ->flatten(),
            'activePrescriptions' => $user->medicalRecords()
                ->with('prescriptions')
                ->latest()
                ->take(5)
                ->get()
                ->pluck('prescriptions')
                ->flatten(),
            'recentActivities' => $recentActivities,
        ];
    }
} 