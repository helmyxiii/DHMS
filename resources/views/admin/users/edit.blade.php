@extends('layouts.app')

@section('title', 'Edit User - Zawar')

@section('content')
<div class="container py-5">
    <h1 class="fw-bold text-white mb-4">Edit User</h1>
    <div class="card card-custom animate mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" placeholder="Name" required>
                    </div>
                    <div class="col-md-4 mb-2">
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" placeholder="Email" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <select name="role" class="form-control" required>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="doctor" {{ $user->role == 'doctor' ? 'selected' : '' }}>Doctor</option>
                            <option value="patient" {{ $user->role == 'patient' ? 'selected' : '' }}>Patient</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <input type="password" name="password" class="form-control" placeholder="New Password (leave blank to keep current)">
                    </div>
                    <div class="col-md-4 mb-2">
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm New Password">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Update User</button>
                <a href="{{ route('admin.users') }}" class="btn btn-secondary mt-2">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection 