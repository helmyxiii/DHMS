<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Zawar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/log-in.css') }}">
</head>
<body class="bg-admin">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light shadow">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/zawar.jpeg') }}" alt="Zawar"> Zawar
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/about') }}">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/appointments') }}">Appointments</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url('/admin/dashboard') }}">Admin</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/doctor/dashboard') }}">Doctor</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/patient/dashboard') }}">Patient</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/login') }}">Sign In</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/register') }}">Sign Up</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/contact') }}">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <form method="POST" action="{{ route('admin.login') }}">
        @csrf
        <div class="form-floating">
            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                   id="email" name="email" placeholder="name@example.com" 
                   value="{{ old('email') }}" required autofocus>
            <label for="email">Email address</label>
        </div>

        <div class="form-floating">
            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                   id="password" name="password" placeholder="Password" required>
            <label for="password">Password</label>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember">Remember me</label>
        </div>

        <button type="submit" class="btn btn-primary">
            Sign In
        </button>
    </form>
@endsection

@section('footer')
    <p class="mb-0">
        <a href="{{ route('doctor.login') }}">Doctor Login</a> | 
        <a href="{{ route('patient.login') }}">Patient Login</a>
    </p>
@endsection 