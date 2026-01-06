@extends('layouts.app')

@section('title', 'Edit Profile')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/medical-record.css') }}">
<style>
body.bg-dashboard {
    background: linear-gradient(to right, rgba(47, 102, 127, 0.7), rgba(0, 198, 255, 0.7)), url('/images/background.webp') no-repeat center center fixed;
    background-size: cover;
    min-height: 100vh;
}
.card-custom {
    background: rgba(0, 48, 80, 0.85);
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
h1.fw-bold, .lead, .text-white, .form-label.text-white {
    color: #fff !important;
}
</style>
@endpush

@push('body-class')
bg-dashboard
@endpush

@section('content')
<div class="container py-5">
    <header class="text-center text-white mb-5">
        <h1 class="fw-bold">Edit Profile</h1>
        <p class="lead">Update your personal and professional information below.</p>
    </header>
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card-custom animate">
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form method="POST" action="{{ route('profile.update') }}" autocomplete="off">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="name" class="form-label text-white">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label text-white">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        @if($user->role === 'doctor')
                        <div class="mb-3">
                            <label for="specialty" class="form-label text-white">Specialty</label>
                            <select class="form-control" id="specialty" name="specialty" required>
                                <option value="">Select Specialty</option>
                                <option value="dentist" {{ old('specialty', $user->specialty) == 'dentist' ? 'selected' : '' }}>Dentist</option>
                                <option value="dermatologist" {{ old('specialty', $user->specialty) == 'dermatologist' ? 'selected' : '' }}>Dermatologist</option>
                                <option value="cardiologist" {{ old('specialty', $user->specialty) == 'cardiologist' ? 'selected' : '' }}>Cardiologist</option>
                                <option value="gp" {{ old('specialty', $user->specialty) == 'gp' ? 'selected' : '' }}>General Practitioner</option>
                            </select>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <button type="submit" class="btn btn-primary px-4">Update Profile</button>
                            <button type="button" class="btn btn-outline-danger px-4" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">Delete Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteAccountModalLabel">Delete Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Yes, Delete My Account</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->isAdmin())
        {{-- Role Management section removed as requested --}}
    @endif
</div>
@endsection 