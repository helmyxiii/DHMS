@extends('layouts.app')

@section('title', 'Doctor Sign In - Zawar')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/log-in.css') }}">
@endsection

@section('body-class', 'bg-sign-in')

@section('content')
<div class="bg-sign-header">
    <div class="container text-center">
        <h1>Doctor Login</h1>
        <p>Sign in to access your doctor dashboard.</p>
    </div>
</div>
<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="sign-form">
        <h2 class="text-center mb-4">Sign In</h2>
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <form method="POST" action="{{ route('doctor.login') }}" class="needs-validation" novalidate>
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter your password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    Sign In
                    <i class="fas fa-sign-in-alt ms-2"></i>
                </button>
            </div>
            @if(Route::has('password.request'))
                <div class="text-center mt-3">
                    <a href="{{ route('password.request') }}" class="text-decoration-none">
                        Forgot your password?
                    </a>
                </div>
            @endif
            <p class="text-center mt-3">
                Don't have an account? 
                <a href="{{ route('doctor.register') }}" class="text-decoration-none">Sign Up</a>
            </p>
        </form>
    </div>
</div>
@endsection 
            </p>
        </form>
    </div>
</div>
@endsection 
            </p>
        </form>
    </div>
</div>
@endsection 