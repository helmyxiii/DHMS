<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contact - Zawar</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom Style -->
    <link rel="stylesheet" href="{{ asset('css/patient.css') }}">
    <style>
        body.bg-dashboard {
            background: linear-gradient(to right, rgba(47, 102, 127, 0.7), rgba(0, 198, 255, 0.7)), url('/images/background.webp') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }
        .card-custom {
            background: rgba(0, 48, 80, 0.85);
            color: #ffffff;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
        .card-custom.animate {
            opacity: 1;
            transform: translateY(0);
        }
        h2.section-title {
            color: #fff;
            margin-top: 2rem;
            margin-bottom: 1rem;
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
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ url('/') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ url('/about') }}">About</a></li>
                @auth
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}" href="{{ route('appointments.index') }}">Appointments</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('patient.medical-records.*') ? 'active' : '' }}" href="{{ route('patient.medical-records.index') }}">Medical Records</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('health-tips.*') ? 'active' : '' }}" href="{{ route('health-tips.index') }}">Health Tips</a></li>
                @endauth
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a></li>
                @guest
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm ms-2" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-light btn-sm ms-2" href="{{ route('register') }}">Sign Up</a>
                    </li>
                @endguest
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle fa-lg me-1"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item d-flex align-items-center" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user-edit me-2 text-primary"></i> <span>Profile</span>
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <input type="hidden" name="redirect" value="/">
                                    <button class="dropdown-item d-flex align-items-center text-danger" type="submit">
                                        <i class="fas fa-sign-out-alt me-2"></i> <span>Logout</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
                </div>
                        </div>
</nav>

<!-- Header -->
<header class="text-center text-white py-5">
    <div class="container">
        <h1 class="fw-bold">Contact Zawar</h1>
        <p class="lead">We're here to help. Reach out anytime.</p>
                        </div>
</header>

<!-- Contact Section -->
<main class="container mb-5">
    <div class="row">
        <!-- Contact Form -->
        <div class="col-md-6">
            <div class="card-custom animate">
                <h4>Send Us a Message</h4>
                <form method="POST" action="{{ route('contact.store') }}">
                        @csrf
                        <div class="mb-3">
                        <label for="name" class="form-label">Your Name</label>
                        <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                        <label for="email" class="form-label">Your Email</label>
                        <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                        <textarea name="message" rows="5" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-light">Send Message</button>
                </form>
                        </div>
                    </div>

        <!-- Contact Info -->
        <div class="col-md-6">
            <div class="card-custom animate">
                <h4>Contact Information</h4>
                <p><i class="fas fa-envelope me-2"></i> support@zawar.com</p>
                <p><i class="fas fa-phone me-2"></i> +20 123 456 7890</p>
                <p><i class="fas fa-map-marker-alt me-2"></i> Galala, Suez, Egypt</p>
            </div>
        </div>
    </div>
</main>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-3">
    <p class="mb-0">&copy; {{ date('Y') }} Zawar. All rights reserved.</p>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
</body>
</html>
