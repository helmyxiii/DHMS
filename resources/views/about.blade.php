@extends('layouts.app')

@section('title', 'About - Zawar')

@push('body-class')
bg-dashboard
@endpush

@push('styles')
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
    h2.section-title, h1.fw-bold, h5.text-white, h5.card-title {
        color: #fff;
        text-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    .btn {
        font-weight: 500;
    }
    .list-group-item {
        background: rgba(255,255,255,0.05);
        color: #fff;
        border: none;
    }
    .nav-link {
        padding: 0.5rem 1rem;
        color: #fff !important;
        transition: all 0.3s ease;
    }
    .nav-link:hover {
        background: rgba(255,255,255,0.1);
        border-radius: 0.5rem;
    }
    .nav-link.active {
        background: rgba(255,255,255,0.2);
        border-radius: 0.5rem;
    }
</style>
@endpush

@section('content')
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
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('profile') ? 'active' : '' }}" href="{{ url('/profile') }}">
                        <i class="fas fa-user me-1"></i> Profile
                    </a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="nav-link border-0 bg-transparent">
                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-5 dashboard-bg">
    <header class="text-center text-white mb-5">
        <h1 class="fw-bold">About Zawar</h1>
        <p class="lead">Learn about our mission and services</p>
    </header>
    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="card-custom animate h-100">
                <h2 class="text-white">Our Mission</h2>
                <p class="text-white">
                    At Zawar, our mission is to simplify healthcare through innovative technology, 
                    ensuring access to the best services for everyone, everywhere. We strive to 
                    make healthcare management seamless and efficient for both healthcare providers 
                    and patients.
                </p>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card-custom animate h-100">
                <h2 class="text-white">Our Vision</h2>
                <p class="text-white">
                    To transform healthcare services by leveraging digital solutions to streamline 
                    operations and enhance patient care, making healthcare accessible to all. We 
                    envision a future where quality healthcare is just a click away.
                </p>
            </div>
        </div>
    </div>
    <h2 class="section-title text-white text-center mb-4">Why Choose Us?</h2>
    <div class="row mb-4">
        <div class="col-md-4 mb-4">
            <div class="card-custom animate h-100 text-center">
                <h5 class="text-white">Easy Appointments</h5>
                <p class="text-white">
                    Book appointments with just a few clicks from your home. 
                    Our intuitive scheduling system makes it easy to find and 
                    book appointments with your preferred healthcare providers.
                </p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card-custom animate h-100 text-center">
                <h5 class="text-white">Trusted Doctors</h5>
                <p class="text-white">
                    Access a network of experienced and reliable healthcare providers. 
                    All our doctors are verified professionals with extensive experience 
                    in their respective fields.
                </p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card-custom animate h-100 text-center">
                <h5 class="text-white">Secure Records</h5>
                <p class="text-white">
                    Your medical records are secure and easily accessible anytime. 
                    We use state-of-the-art encryption to ensure your data remains 
                    private and protected.
                </p>
            </div>
        </div>
    </div>
    <h2 class="section-title text-white text-center mb-4">More Services</h2>
    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="card-custom animate h-100 text-center">
                <h5 class="text-white">24/7 Support</h5>
                <p class="text-white">
                    Our dedicated support team is available round the clock to assist 
                    you with any queries or concerns. We're here to help whenever you need us.
                </p>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card-custom animate h-100 text-center">
                <h5 class="text-white">Digital Prescriptions</h5>
                <p class="text-white">
                    Get your prescriptions digitally and access them anytime. 
                    Share them easily with pharmacies and keep track of your medications.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Animate cards on scroll
    document.addEventListener("DOMContentLoaded", () => {
        const cards = document.querySelectorAll(".card-custom");
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("animate");
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        cards.forEach(card => observer.observe(card));
    });
</script>
@endpush
