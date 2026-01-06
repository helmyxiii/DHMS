<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Zawar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/patient.css') }}">
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

<!-- Hero Section -->
<section class="hero d-flex align-items-center justify-content-center">
    <div class="container text-center">
        <h1 class="display-4 mb-4 animate__animated animate__fadeInDown">Your Health, Our Priority</h1>
        <p class="lead mb-4 animate__animated animate__fadeInUp">Find and book the best doctors, easily and quickly.</p>
        <a href="#specialties" class="btn btn-primary btn-lg animate__animated animate__fadeInUp">
            Explore Specialties
            <i class="fas fa-arrow-down ms-2"></i>
        </a>
    </div>
</section>

<!-- Specialties Section -->
<section id="specialties" class="container py-5 fade-in">
    <h2 class="text-center mb-4">Explore Specialties</h2>
    <div class="row g-4">
        @if(isset($specialties))
            @foreach($specialties as $specialty)
            <div class="col-md-3">
                <div class="card h-100 specialty-card">
                    <div class="card-body text-center">
                        <i class="fas {{ $specialty->icon }} fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">{{ $specialty->name }}</h5>
                        <p class="card-text">{{ $specialty->description }}</p>
                        <a href="{{ route('specialties.show', $specialty) }}" class="btn btn-outline-primary">
                            View {{ $specialty->name }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-4">What Our Users Say</h2>
        <div class="row">
            @foreach($testimonials as $testimonial)
            <div class="col-md-4">
                <div class="testimonial-card text-center">
                    <div class="testimonial-image mb-3">
                        <img src="{{ asset($testimonial->image) }}" alt="{{ $testimonial->name }}" class="rounded-circle">
                    </div>
                    <p class="testimonial-text">"{{ $testimonial->content }}"</p>
                    <footer class="blockquote-footer">
                        {{ $testimonial->name }}
                        <small class="text-muted">{{ $testimonial->title }}</small>
                    </footer>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-3">
    <p>&copy; {{ date('Y') }} Zawar. All rights reserved.</p>
</footer>

<!-- Back to Top Button -->
<button id="backToTop" class="btn btn-primary rounded-circle" aria-label="Back to top">
    <i class="fas fa-arrow-up"></i>
</button>
@endsection

@section('scripts')
<script src="{{ asset('js/home.js') }}"></script>
@endsection