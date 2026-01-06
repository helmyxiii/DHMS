@extends('layouts.app')

@section('title', 'Forgot Password - Zawar')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/log-in.css') }}">
@endsection

@section('body-class', 'bg-sign-in')

@section('content')
<div class="bg-sign-header">
    <div class="container text-center">
        <h1>Forgot Password</h1>
        <p>Enter your email to receive a password reset link.</p>
    </div>
</div>
<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="sign-form">
        <h2 class="text-center mb-4">Reset Password</h2>
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <form method="POST" action="{{ route('password.email') }}" class="needs-validation" novalidate>
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    Send Password Reset Link
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
