@extends('layouts.app')

@section('title', 'User Management - Zawar')

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
    <h1 class="fw-bold mb-4">User Management</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card card-custom animate mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Add New User</h5>
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <input type="text" name="name" class="form-control" placeholder="Name" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <select name="role" class="form-control" required>
                            <option value="">Role</option>
                            <option value="admin">Admin</option>
                            <option value="doctor">Doctor</option>
                            <option value="patient">Patient</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-success mt-2">Add User</button>
            </form>
        </div>
    </div>
    <div class="card card-custom animate">
        <div class="card-body">
            <h5 class="card-title mb-3">All Users</h5>
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('admin.users.delete', $user) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Delete</button>
                            </form>
                        </td>
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