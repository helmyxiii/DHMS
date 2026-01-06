@extends('layouts.app')

@section('title', 'Settings - Zawar')

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
    <h1 class="fw-bold mb-4">Platform Settings</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card card-custom animate">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Hospital Name</label>
                    <input type="text" name="hospital_name" class="form-control" value="{{ old('hospital_name', $settings['hospital_name'] ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contact Email</label>
                    <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contact Phone</label>
                    <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $settings['address'] ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Working Hours</label>
                    <input type="text" name="working_hours" class="form-control" value="{{ old('working_hours', $settings['working_hours'] ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Logo (optional)</label>
                    <input type="file" name="logo" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Update Settings</button>
            </form>
        </div>
    </div>
</div>
@push('body-class')
bg-dashboard
@endpush
@endsection 