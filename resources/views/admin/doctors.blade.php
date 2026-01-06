@extends('layouts.app')

@section('title', 'Doctor Management - Zawar')

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
    .list-group-item {
        background: rgba(255,255,255,0.05);
        color: #fff;
        border: none;
    }
</style>
<div class="container py-5 dashboard-bg">
    <h1 class="fw-bold mb-4">Doctor Management</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card card-custom animate">
        <div class="card-body">
            <h5 class="card-title mb-3">All Doctors</h5>
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Specialties</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($doctors as $doctor)
                    <tr>
                        <td>{{ $doctor->user->name }}</td>
                        <td>{{ $doctor->user->email }}</td>
                        <td>
                            @foreach($doctor->specialties as $specialty)
                                <span class="badge bg-info">{{ $specialty->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            @if($doctor->approved)
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($doctor->approved)
                                <form action="{{ route('admin.doctors.unapprove', $doctor) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-warning">Unapprove</button>
                                </form>
                            @else
                                <form action="{{ route('admin.doctors.approve', $doctor) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-success">Approve</button>
                                </form>
                            @endif
                            <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('admin.doctors.delete', $doctor) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this doctor?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $doctors->links() }}
        </div>
    </div>
    <div class="card card-custom animate mt-4">
        <div class="card-body">
            <h5 class="card-title mb-3">All Specialties</h5>
            <ul class="list-group">
                @foreach($specialties as $specialty)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $specialty->name }}
                        <form action="{{ route('admin.specialties.delete', $specialty) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this specialty?')">Delete</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@push('body-class')
bg-dashboard
@endpush
@endsection 