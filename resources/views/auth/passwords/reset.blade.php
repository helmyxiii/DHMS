@extends('layouts.app')

@section('title', 'Reset Password - Zawar')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/log-in.css') }}">
@endsection

@section('body-class', 'bg-sign-in')

@section('content')
<div class="bg-sign-header">
    <div class="container text-center">
        <h1>Reset Password</h1>
        <p>Enter your new password below.</p>
    </div>
</div>
<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="sign-form">
        <h2 class="text-center mb-4">Reset Password</h2>
        <form method="POST" action="{{ route('password.update') }}" class="needs-validation" novalidate>
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autofocus placeholder="Enter your email">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Create a new password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password-confirm" class="form-label">Confirm Password</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Confirm your new password">
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
