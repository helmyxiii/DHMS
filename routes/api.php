<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\MedicalRecordController;
use App\Http\Controllers\Api\TreatmentController;
use App\Http\Controllers\Api\PrescriptionController;
use App\Http\Controllers\Api\SpecialtyController;
use App\Http\Controllers\Api\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public API Routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Protected API Routes
Route::middleware('auth:sanctum')->group(function () {
    // User Profile
    Route::get('user', function (Request $request) {
        return $request->user();
    });
    Route::post('logout', [AuthController::class, 'logout']);

    // Doctor API Routes
    Route::middleware([])->group(function () {
        Route::apiResource('doctors', DoctorController::class);
        Route::get('doctors/{doctor}/schedule', [DoctorController::class, 'schedule']);
        Route::get('doctors/{doctor}/patients', [DoctorController::class, 'patients']);
        Route::get('doctors/{doctor}/appointments', [DoctorController::class, 'appointments']);
        Route::get('doctors/{doctor}/available-slots', [DoctorController::class, 'availableSlots']);
    });

    // Patient API Routes
    Route::middleware([])->group(function () {
        Route::apiResource('patients', PatientController::class);
        Route::get('patients/{patient}/medical-history', [PatientController::class, 'medicalHistory']);
        Route::get('patients/{patient}/appointments', [PatientController::class, 'appointments']);
    });

    // Appointment API Routes
    Route::middleware([])->group(function () {
        Route::apiResource('appointments', AppointmentController::class);
        Route::post('appointments/{appointment}/cancel', [AppointmentController::class, 'cancel']);
        Route::post('appointments/{appointment}/complete', [AppointmentController::class, 'complete']);
        Route::get('appointments/calendar', [AppointmentController::class, 'calendar']);
    });

    // Medical Record API Routes
    Route::middleware([])->group(function () {
        Route::apiResource('medical-records', MedicalRecordController::class);
        Route::get('medical-records/{medicalRecord}/treatments', [MedicalRecordController::class, 'treatments']);
        Route::get('medical-records/{medicalRecord}/prescriptions', [MedicalRecordController::class, 'prescriptions']);
    });

    // Treatment API Routes
    Route::middleware([])->group(function () {
        Route::apiResource('treatments', TreatmentController::class);
        Route::post('treatments/{treatment}/complete', [TreatmentController::class, 'complete']);
        Route::post('treatments/{treatment}/cancel', [TreatmentController::class, 'cancel']);
    });

    // Prescription API Routes
    Route::middleware([])->group(function () {
        Route::apiResource('prescriptions', PrescriptionController::class);
        Route::get('prescriptions/{prescription}/print', [PrescriptionController::class, 'print']);
    });

    // Specialty API Routes (Admin only)
    Route::middleware([])->group(function () {
        Route::apiResource('specialties', SpecialtyController::class);
    });

    // Dashboard Routes
    Route::get('/dashboard/doctor', [DashboardController::class, 'getDoctorDashboardData']);
    Route::get('/dashboard/patient', [DashboardController::class, 'getPatientDashboardData']);
    Route::get('/dashboard/admin', [DashboardController::class, 'getAdminDashboardData']);
}); 