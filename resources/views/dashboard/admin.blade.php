@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<style>
    body.bg-admin-dashboard {
        background: url('{{ asset('images/medical-hexagons.jpg') }}') no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
    }
    .dashboard-bg {
        background: rgba(255,255,255,0.88);
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        padding: 2rem;
    }
</style>
@endsection

@section('content')
<div class="container py-4 dashboard-bg">
    <h1 class="mb-4">Admin Dashboard</h1>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="display-6">{{ $stats['total_users'] ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Doctors</h5>
                    <p class="display-6">{{ $stats['total_doctors'] ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Patients</h5>
                    <p class="display-6">{{ $stats['total_patients'] ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Appointments</h5>
                    <p class="display-6">{{ $stats['total_appointments'] ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row mb-4">
        <div class="col-md-3">
            <a href="{{ route('admin.users') }}" class="btn btn-outline-primary w-100 mb-2">Manage Users</a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.reports') }}" class="btn btn-outline-info w-100 mb-2">View Reports</a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.settings') }}" class="btn btn-outline-secondary w-100 mb-2">Settings</a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-success w-100 mb-2">Refresh Dashboard</a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.medical-records.index') }}" class="btn btn-outline-danger w-100 mb-2">Medical Records</a>
        </div>
    </div>

    <!-- Recent Appointments -->
    @if(isset($stats['recent_appointments']) && count($stats['recent_appointments']) > 0)
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            Recent Appointments
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stats['recent_appointments'] as $appointment)
                    <tr>
                        <td>{{ $appointment->appointment_date }}</td>
                        <td>{{ $appointment->time_slot ?? 'N/A' }}</td>
                        <td>{{ $appointment->patient ? $appointment->patient->name : 'N/A' }}</td>
                        <td>{{ $appointment->doctor ? $appointment->doctor->name : 'N/A' }}</td>
                        <td>{{ ucfirst($appointment->status) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection

@push('body-class')
bg-admin-dashboard
@endpush 