<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In - Zawar</title>

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/patient.css') }}">
<link rel="stylesheet" href="{{ asset('css/log-in.css') }}">

    <style>
        body.bg-dashboard {
            background: linear-gradient(to right, rgba(47, 102, 127, 0.7), rgba(0, 198, 255, 0.7)), url('/images/background.webp') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }
        .sign-form {
            background: rgba(0, 48, 80, 0.85);
            color: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 12px 48px rgba(0, 0, 0, 0.1);
            padding: 2rem 2.5rem;
            margin: 0 auto;
            max-width: 400px;
        }
        .sign-form h2 {
            margin-bottom: 2rem;
            text-align: center;
        }
        .card-body {
            background: rgba(255, 255, 255, 0.82);
            border-radius: 1.25rem;
            box-shadow: 0 2px 12px 0 rgba(37, 99, 235, 0.07);
            padding: 2rem 1.5rem;
            margin-bottom: 1rem;
            transition: box-shadow 0.2s;
        }
        .card:hover .card-body {
            box-shadow: 0 6px 24px 0 rgba(37, 99, 235, 0.13);
        }
    </style>
</head>
<body class="bg-dashboard">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/zawar.jpeg') }}" alt="Zawar" height="40"> Zawar
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/about') }}">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm ms-2" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-light btn-sm ms-2" href="{{ route('register') }}">Sign Up</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <header class="bg-patient-header text-center">
        <div class="container">
        <h1>Welcome Back</h1>
        <p>Please sign in to continue accessing our healthcare services.</p>
    </div>
    </header>

    <!-- Sign In Form Section -->
    <main class="container py-5">
<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="sign-form">
        <h2 class="text-center mb-4">Sign In</h2>
        
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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
        <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
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
            <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="">Select Role</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="doctor" {{ old('role') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                    <option value="patient" {{ old('role') == 'patient' ? 'selected' : '' }}>Patient</option>
                </select>
                @error('role')
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
                <a href="{{ route('register') }}" class="text-decoration-none">Sign Up</a>
            </p>
        </form>
    </div>
</div>
    </main>

    <!-- Footer -->
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container text-center">
            <span class="text-muted">&copy; {{ date('Y') }} Zawar Hospital Management System. All rights reserved.</span>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function() {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>
    
    <style>
        .navbar-nav .btn {
            padding: 0.4rem 0.9rem;
            font-size: 0.875rem;
            margin-top: 4px;
        }
        .navbar .dropdown-menu {
            left: auto !important;
            right: 0;
            top: 100%;
            transform: none !important;
        }
    </style>

</body>
</html> 