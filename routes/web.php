<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\DoctorAuthController;
use App\Http\Controllers\Auth\PatientAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\HealthTipController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Auth;


// Public Routes
Route::get('/', function () {
    return redirect('/home');
});

// Static Pages
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Admin
    Route::get('admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('admin/login', [AdminAuthController::class, 'login']);
    

    // Doctor
    Route::get('doctor/login', [DoctorAuthController::class, 'showLoginForm'])->name('doctor.login');
    Route::post('doctor/login', [DoctorAuthController::class, 'login']);
    Route::get('doctor/register', [DoctorAuthController::class, 'showRegistrationForm'])->name('doctor.register');
    Route::post('doctor/register', [DoctorAuthController::class, 'register']);

    // Patient
    Route::get('patient/login', [PatientAuthController::class, 'showLoginForm'])->name('patient.login');
    Route::post('patient/login', [PatientAuthController::class, 'login']);
    Route::get('patient/register', [PatientAuthController::class, 'showRegistrationForm'])->name('patient.register');
    Route::post('patient/register', [PatientAuthController::class, 'register']);

    // General
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::get('/register/patient', [RegisterController::class, 'showPatientRegistrationForm'])->name('register.patient');
    Route::post('/register/patient', [RegisterController::class, 'registerPatient']);
    Route::get('/register/doctor', [RegisterController::class, 'showDoctorRegistrationForm'])->name('register.doctor');
    Route::post('/register/doctor', [RegisterController::class, 'registerDoctor']);
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Logout Routes
Route::post('admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
Route::post('doctor/logout', [DoctorAuthController::class, 'logout'])->name('doctor.logout');
Route::post('patient/logout', [PatientAuthController::class, 'logout'])->name('patient.logout');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard (shared)
Route::get('/home', [App\Http\Controllers\DashboardController::class, 'index'])->name('home')->middleware('auth');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('medical-records', MedicalRecordController::class);


    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');

    // Reports & Settings
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/reports/appointments', [AdminController::class, 'appointmentReports'])->name('reports.appointments');
    Route::get('/reports/users', [AdminController::class, 'userReports'])->name('reports.users');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');

    Route::get('change-requests', [AdminController::class, 'changeRequests'])->name('changeRequests');
    Route::post('change-requests/{changeRequest}/process', [AdminController::class, 'processChangeRequest'])->name('changeRequests.process');
});

// Standalone admin features route with closure middleware
Route::get('/admin/features', function () {
    if (!Auth::check() || !Auth::user()->hasRole('admin')) {
        abort(403);
    }
    return view('admin.features');
})->name('admin.features');

Route::get('/admin/users', [App\Http\Controllers\AdminController::class, 'users'])->name('admin.users');

Route::get('/admin/reports', [App\Http\Controllers\AdminController::class, 'reports'])->name('admin.reports');

Route::get('/admin/settings', function () {
    if (!Auth::check() || !Auth::user()->hasRole('admin')) {
        abort(403);
    }
    return view('admin.settings');
})->name('admin.settings');

Route::get('/admin/doctors', [App\Http\Controllers\AdminController::class, 'doctors'])->name('admin.doctors');

Route::get('/admin/announcements', function () {
    if (!Auth::check() || !Auth::user()->hasRole('admin')) {
        abort(403);
    }
    return view('admin.announcements');
})->name('admin.announcements');

Route::get('/admin/logs', function () {
    if (!Auth::check() || !Auth::user()->hasRole('admin')) {
        abort(403);
    }
    return view('admin.logs');
})->name('admin.logs');

// Doctor Routes
Route::middleware(['auth'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DoctorDashboardController::class, 'index'])->name('dashboard');
    Route::resource('medical-records', MedicalRecordController::class);
    Route::get('/profile', [DoctorController::class, 'profile'])->name('profile');
    Route::put('/profile', [DoctorController::class, 'updateProfile'])->name('profile.update');
    Route::resource('schedules', ScheduleController::class);
    Route::post('schedules/{schedule}/toggle', [ScheduleController::class, 'toggleAvailability'])->name('schedules.toggle');
    Route::resource('appointments', AppointmentController::class);
    Route::resource('health-tips', HealthTipController::class);
    Route::resource('reports', ReportController::class);
    Route::get('reports/{report}/download', [ReportController::class, 'download'])->name('reports.download');
    Route::get('change-requests', [DoctorController::class, 'changeRequests'])->name('changeRequests');
    Route::post('change-requests/{changeRequest}/process', [DoctorController::class, 'processChangeRequest'])->name('changeRequests.process');
});

// Patient routes
Route::middleware(['auth'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');
    Route::get('/appointments', [PatientController::class, 'appointments'])->name('appointments.index');
    Route::get('/appointments/book', [PatientController::class, 'bookAppointment'])->name('appointments.book');
    Route::post('/appointments/slots', [PatientController::class, 'getAvailableSlots'])->name('appointments.slots');
    Route::post('/appointments', [PatientController::class, 'storeAppointment'])->name('appointments.store');
    Route::post('/appointments/{appointment}/cancel', [PatientController::class, 'cancelAppointment'])->name('appointments.cancel');
    Route::get('/medical-records', [PatientController::class, 'medicalRecords'])->name('medical-records.index');
    Route::get('/medical-records/{record}', [PatientController::class, 'showMedicalRecord'])->name('medical-records.show');
    Route::get('/doctors', [PatientController::class, 'doctors'])->name('doctors.index');
    Route::get('/doctors/{doctor}', [PatientController::class, 'showDoctor'])->name('doctors.show');
    Route::get('/prescriptions', [PatientController::class, 'prescriptions'])->name('prescriptions.index');
    Route::get('/bills', [PatientController::class, 'bills'])->name('bills.index');
    Route::get('/profile', [PatientController::class, 'profile'])->name('profile');
    Route::put('/profile', [PatientController::class, 'updateProfile'])->name('profile.update');
    Route::post('/appointments/request-change', [PatientController::class, 'requestChange'])->name('appointments.request-change');
    Route::get('/appointments/request-change/{appointment}', [PatientController::class, 'showRequestChangeForm'])->name('appointments.request-change.form');
});

// Shared routes for admin and doctor
Route::middleware(['auth'])->group(function () {
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::get('/appointments/{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
});

// Common Auth Routes
Route::middleware('auth')->group(function () {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Add appointments resource route
    Route::resource('appointments', AppointmentController::class);
});

Route::get('specialties', [SpecialtyController::class, 'index'])->name('specialties.index');
Route::get('specialties/{specialty}', [SpecialtyController::class, 'show'])->name('specialties.show');
Route::get('health-tips', [HealthTipController::class, 'index'])->name('health-tips.index');
Route::get('health-tips/{healthTip}', [HealthTipController::class, 'show'])->name('health-tips.show');
Route::middleware(['testMiddleware'])->get('/test-middleware', function () {
    return 'TestMiddleware is working!';
});

// Role management routes
Route::middleware(['auth'])->group(function () {
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
});

// Add a new route for selecting doctors based on specialty
Route::get('/doctors/select/{specialty}', function ($specialty) {
    return view('doctors.select', ['specialty' => $specialty]);
})->name('doctors.select');

// Show doctors for a specialty
Route::get('/appointments/select-doctor/{specialty}', [\App\Http\Controllers\AppointmentController::class, 'selectDoctor'])->name('appointments.selectDoctor');
// Show appointment form for a specific doctor
Route::get('/appointments/create/{doctor}', [\App\Http\Controllers\AppointmentController::class, 'createForDoctor'])->name('appointments.createForDoctor');

Route::get('/doctor/features', function () {
    if (!Auth::check() || !Auth::user()->hasRole('doctor')) {
        abort(403, 'Unauthorized');
    }
    return view('doctor.features');
})->name('doctor.features');

// Doctor management actions
Route::patch('/admin/doctors/{doctor}/approve', [App\Http\Controllers\AdminController::class, 'approveDoctor'])->name('admin.doctors.approve');
Route::patch('/admin/doctors/{doctor}/unapprove', [App\Http\Controllers\AdminController::class, 'unapproveDoctor'])->name('admin.doctors.unapprove');
Route::get('/admin/doctors/{doctor}/edit', [App\Http\Controllers\AdminController::class, 'editDoctor'])->name('admin.doctors.edit');
Route::put('/admin/doctors/{doctor}', [App\Http\Controllers\AdminController::class, 'updateDoctor'])->name('admin.doctors.update');
Route::delete('/admin/doctors/{doctor}', [App\Http\Controllers\AdminController::class, 'deleteDoctor'])->name('admin.doctors.delete');
// Specialty delete
Route::delete('/admin/specialties/{specialty}', [App\Http\Controllers\AdminController::class, 'deleteSpecialty'])->name('admin.specialties.delete');

Route::post('/admin/announcements', [App\Http\Controllers\AdminController::class, 'sendAnnouncement'])->name('admin.announcements.send');