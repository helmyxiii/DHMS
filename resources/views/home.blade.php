@extends('layouts.app')

@section('title', 'Dashboard - Zawar')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/appoint.css') }}">
<style>
    body.bg-dashboard {
        background: linear-gradient(90deg, #2563eb 0%, #60a5fa 100%), url('/images/background.webp') no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
    }
    .dashboard-bg {
        background: linear-gradient(rgba(255,255,255,0.85), rgba(255,255,255,0.85)), url('/images/medical-hexagons.jpg') center center/cover no-repeat;
        border-radius: 2.5rem;
        box-shadow: 0 8px 48px 0 rgba(37,99,235,0.10), 0 1.5px 8px 0 rgba(0,0,0,0.04);
        border: 1.5px solid #e0e7ff;
        padding: 2.5rem 2rem;
        margin-top: 3.5rem;
        margin-bottom: 3.5rem;
        backdrop-filter: blur(6px);
    }
</style>
@endsection

@section('content')
<div class="container dashboard-bg">
    <!-- Hero Section for Dashboard -->
    <section class="hero d-flex align-items-center justify-content-center">
        <div class="container text-center">
            <h1 class="display-4 mb-4">Welcome, {{ Auth::user()->name }}!</h1>
            <a href="{{ route('appointments.index') }}" class="btn btn-primary btn-lg">
                View Appointments
                <i class="fas fa-calendar-check ms-2"></i>
            </a>
        </div>
    </section>
    <!-- Specialties Section -->
    <section class="mt-5">
        <h2 class="text-center mb-4">Find a Specialist</h2>
        <div class="row g-4 justify-content-center">
            <div class="col-md-3">
                <div class="dashboard-card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-tooth fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Dentists</h5>
                        <p class="card-text">Find the best dentists near you.</p>
                        <a href="{{ route('appointments.selectDoctor', ['specialty' => 'dentist']) }}" class="btn btn-outline-primary">Find Dentists</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-allergies fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Dermatologists</h5>
                        <p class="card-text">Skin care specialists available now.</p>
                        <a href="{{ route('appointments.selectDoctor', ['specialty' => 'dermatologist']) }}" class="btn btn-outline-primary">Find Dermatologists</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-heartbeat fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Cardiologists</h5>
                        <p class="card-text">Book the best heart specialists.</p>
                        <a href="{{ route('appointments.selectDoctor', ['specialty' => 'cardiologist']) }}" class="btn btn-outline-primary">Find Cardiologists</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-user-md fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">General Practitioners</h5>
                        <p class="card-text">Consult with a GP now.</p>
                        <a href="{{ route('appointments.selectDoctor', ['specialty' => 'gp']) }}" class="btn btn-outline-primary">Find GPs</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('body-class')
bg-dashboard
@endpush
