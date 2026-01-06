@extends('layouts.app')

@section('title', 'Announcements - Zawar')

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
    <h1 class="fw-bold mb-4">Announcements</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card card-custom animate mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.announcements.send') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Message</label>
                    <textarea name="message" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Announcement</button>
            </form>
        </div>
    </div>
</div>
@push('body-class')
bg-dashboard
@endpush
@endsection 