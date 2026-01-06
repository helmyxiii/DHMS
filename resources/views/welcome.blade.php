@extends('layouts.app')

@section('title', 'Home - Zawar')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link rel="stylesheet" href="{{ asset('css/appoint.css') }}">
<style>
    body.bg-dashboard {
        background: linear-gradient(90deg, #2563eb 0%, #60a5fa 100%), url('/images/background.webp') no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
    }
    .dashboard-bg {
        background: linear-gradient(rgba(255,255,255,0.85), rgba(255,255,255,0.85)), url('/images/medical-hexagons.jpg') center center/cover no-repeat;
        border-radius: 2.5rem;
        box-shadow: 0 8px 48px 0 rgba(37,99,235,0.10), 0 1.5px 8px 0 rgba(0,0,0,0.04);
        border: 1.5px solid #e0e7ff;
        padding: 2.5rem 2rem;
        margin-top: 3.5rem;
        margin-bottom: 3.5rem;
        backdrop-filter: blur(6px);
    }
</style>
@endsection

@section('content')
<div class="container dashboard-bg">
<!-- Hero Section -->
<section class="hero d-flex align-items-center justify-content-center home-hero-bg">
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
<section id="specialties" class="py-5 fade-in home-specialties-bg">
    <div class="row g-4 justify-content-center">
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

<!-- Features Section -->
<section class="features py-5 home-features-bg">
    <div class="container">
        <h2 class="text-center mb-5">Why Choose Zawar?</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <i class="fas fa-user-md fa-3x text-primary mb-3"></i>
                    <h4>Expert Doctors</h4>
                    <p>Access to qualified and experienced healthcare professionals.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <i class="fas fa-calendar-check fa-3x text-primary mb-3"></i>
                    <h4>Easy Booking</h4>
                    <p>Book appointments online with just a few clicks.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <i class="fas fa-clock fa-3x text-primary mb-3"></i>
                    <h4>24/7 Support</h4>
                    <p>Round-the-clock assistance for all your healthcare needs.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-4">What Our Users Say</h2>
        <div class="row">
            @if(isset($testimonials))
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
            @endif
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="cta py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="mb-4">Ready to Book Your Appointment?</h2>
        <p class="lead mb-4">Join thousands of satisfied patients who trust Zawar for their healthcare needs.</p>
        <div class="d-flex justify-content-center gap-3">
            @php
                $createRoute = route('patient.appointments.create');
                if(auth()->check()) {
                    if(auth()->user()->isDoctor()) {
                        $createRoute = route('doctor.appointments.create');
                    } elseif(auth()->user()->isPatient()) {
                        $createRoute = route('patient.appointments.create');
                    }
                }
            @endphp
            <a href="{{ $createRoute }}" class="btn btn-light btn-lg">
                Book Now
                <i class="fas fa-calendar-plus ms-2"></i>
            </a>
            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">
                Sign Up
                <i class="fas fa-user-plus ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="statistics py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3">
                <div class="stat-card">
                    <i class="fas fa-user-md fa-3x text-primary mb-3"></i>
                    <h3 class="counter">{{ $stats->doctors ?? 0 }}</h3>
                    <p>Expert Doctors</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <i class="fas fa-users fa-3x text-primary mb-3"></i>
                    <h3 class="counter">{{ $stats->patients ?? 0 }}</h3>
                    <p>Happy Patients</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <i class="fas fa-calendar-check fa-3x text-primary mb-3"></i>
                    <h3 class="counter">{{ $stats->appointments ?? 0 }}</h3>
                    <p>Appointments</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <i class="fas fa-star fa-3x text-primary mb-3"></i>
                    <h3 class="counter">{{ $stats->rating ?? 0 }}</h3>
                    <p>Average Rating</p>
                </div>
            </div>
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
</div>
@endsection

@push('body-class')
bg-dashboard
@endpush

@section('scripts')
<script src="{{ asset('js/home.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize counters
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        const target = parseInt(counter.textContent);
        let count = 0;
        const duration = 2000; // 2 seconds
        const increment = target / (duration / 16); // 60fps

        const updateCount = () => {
            count += increment;
            if (count < target) {
                counter.textContent = Math.ceil(count);
                requestAnimationFrame(updateCount);
            } else {
                counter.textContent = target;
            }
        };

        // Start counting when element is in view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    updateCount();
                    observer.unobserve(entry.target);
                }
            });
        });

        observer.observe(counter);
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Back to Top Button
    const backToTopButton = document.getElementById('backToTop');
    if (backToTopButton) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.style.display = 'block';
            } else {
                backToTopButton.style.display = 'none';
            }
        });

        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
});
</script>
@endsection
