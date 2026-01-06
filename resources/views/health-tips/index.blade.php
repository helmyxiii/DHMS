<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Health Tips - Zawar</title>

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/patient.css') }}">
    <link rel="stylesheet" href="{{ asset('css/health-tips.css') }}">

    <style>
        body.bg-dashboard {
            background: linear-gradient(to right, rgba(47, 102, 127, 0.7), rgba(0, 198, 255, 0.7)), url('/images/background.webp') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }
        .health-tip-card {
            background: rgba(167, 190, 206, 0.85);
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
        .health-tip-card.animate {
            opacity: 1;
            transform: translateY(0);
        }
        .health-tips-header {
            color: #fff;
            text-align: center;
            margin-bottom: 2rem;
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
        @keyframes fadeInCard {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: none; }
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
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}" href="{{ route('appointments.index') }}">Appointments</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('patient.medical-records.*') ? 'active' : '' }}" href="{{ route('patient.medical-records.index') }}">Medical Records</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('health-tips.*') ? 'active' : '' }}" href="{{ route('health-tips.index') }}">Health Tips</a></li>
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

    <!-- Page Header -->
    <header class="text-center text-white py-5">
        <div class="container">
            <h1 class="fw-bold">Health Tips</h1>
            <p class="lead">Learn how to live a healthier lifestyle with our tips.</p>
        </div>
    </header>

    <!-- Health Tips Section -->
    <main class="container py-5">
        <div class="dashboard-bg text-white">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <h3 class="text-white mb-4">Search and Filter Health Tips</h3>
                    <form method="GET" class="mb-4 d-flex gap-2 flex-wrap">
                        <input type="text" name="search" class="form-control" placeholder="Search health tips..." value="{{ request()->get('search') }}">
                        <select name="category" class="form-select" style="max-width:200px;">
                            <option value="">All Categories</option>
                            <option value="Tips for Sleep" {{ request('category') === 'Tips for Sleep' ? 'selected' : '' }}>Tips for Sleep</option>
                            <option value="Tips for Nutrition" {{ request('category') === 'Tips for Nutrition' ? 'selected' : '' }}>Tips for Nutrition</option>
                            <option value="Tips for Heart Patients" {{ request('category') === 'Tips for Heart Patients' ? 'selected' : '' }}>Tips for Heart Patients</option>
                            <option value="Tips for Sugar Patients" {{ request('category') === 'Tips for Sugar Patients' ? 'selected' : '' }}>Tips for Sugar Patients</option>
                            <option value="Routine Care" {{ request('category') === 'Routine Care' ? 'selected' : '' }}>Routine Care</option>
                        </select>
                        <button class="btn btn-primary" type="submit">Filter</button>
                    </form>
                </div>
            </div>

            <div class="row">
                @foreach($healthTips as $tip)
                    <div class="col-md-4 mb-4">
                        <div class="health-tip-card animate">
                            <div class="health-tip-title">{{ $tip->title }}</div>
                            <div class="health-tip-content">{{ Str::limit($tip->content, 100) }}</div>
                            <button class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#tipModal{{ $tip->id }}">Read More</button>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="tipModal{{ $tip->id }}" tabindex="-1" aria-labelledby="tipModalLabel{{ $tip->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="tipModalLabel{{ $tip->id }}">{{ $tip->title }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="health-tip-content">{{ $tip->content }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p class="mb-0">&copy; {{ date('Y') }} Zawar. All rights reserved.</p>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const cards = document.querySelectorAll(".health-tip-card");

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
