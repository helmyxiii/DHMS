<?php

namespace App\Http\Controllers\Api;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends ApiController
{
    public function getDoctorDashboardData()
    {
        try {
            $doctor = Auth::user()->doctor;
            
            $data = [
                'stats' => [
                    'totalPatients' => $doctor->patients()->count(),
                    'totalAppointments' => $doctor->appointments()->count(),
                    'totalMedicalRecords' => $doctor->medicalRecords()->count(),
                    'availableSlots' => $doctor->schedules()
                        ->where('is_available', true)
                        ->where('date', '>=', now()->toDateString())
                        ->count(),
                ],
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
                    ->with('patient')
                    ->latest()
                    ->take(5)
                    ->get(),
                'recentAppointments' => $doctor->appointments()
                    ->with('patient')
                    ->latest()
                    ->paginate(10),
                'todaySchedule' => $doctor->schedules()
                    ->where('date', today())
                    ->orderBy('start_time')
                    ->paginate(10),
            ];

            return $this->successResponse($data, 'Doctor dashboard data retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Error retrieving doctor dashboard data: ' . $e->getMessage());
        }
    }

    public function getPatientDashboardData()
    {
        try {
            $patient = Auth::user()->patient;
            
            $data = [
                'stats' => [
                    'totalAppointments' => $patient->appointments()->count(),
                    'upcomingAppointments' => $patient->appointments()
                        ->where('appointment_date', '>=', now())
                        ->count(),
                    'medicalRecords' => $patient->medicalRecords()->count(),
                ],
                'recentAppointments' => $patient->appointments()
                    ->with('doctor')
                    ->latest()
                    ->take(5)
                    ->get()
            ];

            return $this->successResponse($data, 'Patient dashboard data retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Error retrieving patient dashboard data: ' . $e->getMessage());
        }
    }

    public function getAdminDashboardData()
    {
        try {
            $data = [
                'stats' => [
                    'totalDoctors' => Doctor::count(),
                    'totalPatients' => Patient::count(),
                    'totalAppointments' => Appointment::count(),
                    'totalMedicalRecords' => MedicalRecord::count(),
                ],
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

            return $this->successResponse($data, 'Admin dashboard data retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Error retrieving admin dashboard data: ' . $e->getMessage());
        }
    }
} 