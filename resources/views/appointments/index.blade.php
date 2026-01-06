<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Appointments - Zawar</title>

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom CSS -->
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
        h2.section-title {
            color: #fff;
            margin-top: 2rem;
            margin-bottom: 1rem;
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
                    @auth
                        @if(!Auth::user()->isDoctor())
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}" href="{{ route('appointments.index') }}">Appointments</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('patient.medical-records.*') ? 'active' : '' }}" href="{{ route('patient.medical-records.index') }}">Medical Records</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('health-tips.*') ? 'active' : '' }}" href="{{ route('health-tips.index') }}">Health Tips</a></li>
                        @endif
                    @else
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
            @php $isDoctor = auth()->check() && auth()->user()->hasRole('doctor'); @endphp
            @if($isDoctor)
                <h1 class="fw-bold">Check and manage your upcoming appointments</h1>
                <p class="lead">View, update, and manage all your scheduled appointments with patients.</p>
            @else
                <h1 class="fw-bold">Book and manage your appointments</h1>
                <p class="lead">Easily schedule, manage, and view your healthcare appointments.</p>
            @endif
        </div>
    </header>

    <!-- Appointments Section -->
    <main class="container py-5">
        <div class="dashboard-bg text-white">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    @if($isDoctor)
                        <div class="card-custom animate mb-4">
                            <div class="card-body">
                                <h3 class="text-white mb-4">Upcoming Appointments</h3>
                                <div class="table-responsive">
                                    <table class="table table-hover table-dark table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Patient</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($appointments as $appointment)
                                                <tr>
                                                    <td>{{ $appointment->patient->name ?? 'Unknown' }}</td>
                                                    <td>{{ $appointment->date }}</td>
                                                    <td>{{ $appointment->time }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $appointment->status === 'cancelled' ? 'danger' : 'success' }}">
                                                            {{ ucfirst($appointment->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('doctor.appointments.show', $appointment) }}" class="btn btn-sm btn-info">View</a>
                                                        <a href="{{ route('doctor.appointments.edit', $appointment) }}" class="btn btn-sm btn-primary">Edit</a>
                                                        <form action="{{ route('doctor.appointments.destroy', $appointment) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this appointment?')">Cancel</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-white">No upcoming appointments found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-center mt-2">
                                    {{ $appointments->links() }}
                                </div>
                            </div>
                        </div>
                    @else
                        <h3 class="text-white mb-4">Request an Appointment</h3>
                        <form method="POST" action="{{ route('appointments.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="date" class="form-label text-white">Date</label>
                                <input type="date" id="date" name="date" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="time" class="form-label text-white">Time</label>
                                <input type="time" id="time" name="time" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="reason" class="form-label text-white">Reason</label>
                                <textarea id="reason" name="reason" rows="4" class="form-control" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Book Appointment</button>
                        </form>
                    @endif
                </div>
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
