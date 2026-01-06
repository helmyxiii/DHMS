@extends('layouts.app')

@section('title', 'Reports - Zawar')

@section('content')
<style>
    body.bg-dashboard {
        background: linear-gradient(to right, rgba(47, 102, 127, 0.7), rgba(0, 198, 255, 0.7)), url('/images/background.webp') no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
    }
    .dashboard-bg {
        background: rgba(0, 48, 80, 0.85);
        border-radius: 1rem;
        box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        padding: 2rem 2rem 1rem 2rem;
        margin-bottom: 2rem;
    }
    .card-custom {
        background: rgba(255,255,255,0.08);
        color: #ffffff;
        border: none;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        transition: transform 0.5s ease, opacity 0.5s ease;
        opacity: 0;
        transform: translateY(40px);
    }
    .card-custom.animate {
        opacity: 1;
        transform: translateY(0);
    }
    h1.fw-bold, h5.text-white, h5.card-title {
        color: #fff;
        text-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    .btn {
        font-weight: 500;
    }
</style>
<div class="container py-5 dashboard-bg">
    <h1 class="fw-bold mb-4">Reports</h1>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card card-custom animate text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Appointments</h5>
                    <p class="display-6">{{ $appointmentStats['total'] }}</p>
                    <span class="badge bg-success">Completed: {{ $appointmentStats['completed'] }}</span>
                    <span class="badge bg-warning text-dark">Pending: {{ $appointmentStats['pending'] }}</span>
                    <span class="badge bg-danger">Cancelled: {{ $appointmentStats['cancelled'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-custom animate text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="display-6">{{ $userStats['total'] }}</p>
                    <span class="badge bg-info">Doctors: {{ $userStats['doctors'] }}</span>
                    <span class="badge bg-primary">Patients: {{ $userStats['patients'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-custom animate text-center">
                <div class="card-body">
                    <h5 class="card-title">Top Specialties</h5>
                    <p class="display-6">{{ $specialtyStats->sum('doctors_count') }} Doctors</p>
                    <ul class="list-unstyled text-white">
                        @foreach($specialtyStats->sortByDesc('doctors_count')->take(3) as $specialty)
                            <li>{{ $specialty->name }} <span class="badge bg-secondary">{{ $specialty->doctors_count }} doctors</span></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom animate mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Appointments Per Month ({{ date('Y') }})</h5>
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Appointments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointmentStats['monthly'] as $row)
                        <tr>
                            <td>{{ DateTime::createFromFormat('!m', $row->month)->format('F') }}</td>
                            <td>{{ $row->count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card card-custom animate">
        <div class="card-body">
            <h5 class="card-title mb-3">User Registrations Per Month ({{ date('Y') }})</h5>
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Registrations</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userStats['monthly_registrations'] as $row)
                        <tr>
                            <td>{{ DateTime::createFromFormat('!m', $row->month)->format('F') }}</td>
                            <td>{{ $row->count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@push('body-class')
bg-dashboard
@endpush
@endsection 