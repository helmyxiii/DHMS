<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="1X0CXE0R0mNpAeYnQfg8FGMBKOZpjfc2ZHjHXKhi">
    
    <title>Admin Features - Zawar</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="http://localhost:8000/css/patient.css">
    <style>
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
        h2.section-title, h1.fw-bold {
            color: #fff;
            margin-top: 2rem;
            margin-bottom: 1rem;
            text-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        .card-title {
            color: #fff;
        }
        .btn {
            font-weight: 500;
        }
    </style>
</head>
<body class="bg-dashboard">
    
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="http://localhost:8000">
                <img src="http://localhost:8000/images/zawar.jpeg" alt="Zawar" height="40"> Zawar
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="http://localhost:8000">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="http://localhost:8000/profile">
                            <i class="fas fa-user me-1"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="http://localhost:8000/logout" class="d-inline">
                            <input type="hidden" name="_token" value="1X0CXE0R0mNpAeYnQfg8FGMBKOZpjfc2ZHjHXKhi" autocomplete="off">
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
            <h1 class="fw-bold">Admin Dashboard</h1>
            <p class="lead">Access all administrative tools and management features in one place.</p>
        </header>
        <h2 class="section-title text-white text-center mb-4">What Can You Manage?</h2>
        <div class="row mb-4">
            <!-- Manage Users -->
            <div class="col-md-4 mb-4">
                <div class="card-custom animate h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-users-cog fa-3x mb-3 text-primary"></i>
                        <h5 class="card-title">Manage Users</h5>
                        <p class="card-text">Add, update, or remove users in the system.</p>
                        <a href="{{ route('admin.users') }}" class="btn btn-primary mt-2">Go to User Management</a>
                    </div>
                </div>
            </div>
            <!-- View Reports -->
            <div class="col-md-4 mb-4">
                <div class="card-custom animate h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-chart-line fa-3x mb-3 text-success"></i>
                        <h5 class="card-title">View Reports</h5>
                        <p class="card-text">Access hospital analytics and performance reports.</p>
                        <a href="{{ route('admin.reports') }}" class="btn btn-success mt-2">Go to Reports</a>
                    </div>
                </div>
            </div>
            <!-- Configure Settings -->
            <div class="col-md-4 mb-4">
                <div class="card-custom animate h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-cogs fa-3x mb-3 text-info"></i>
                        <h5 class="card-title">Configure Settings</h5>
                        <p class="card-text">Update platform settings and manage system preferences.</p>
                        <a href="{{ route('admin.settings') }}" class="btn btn-info mt-2">Go to Settings</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <!-- Manage Doctors -->
            <div class="col-md-6 mb-4">
                <div class="card-custom animate h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-user-md fa-3x mb-3 text-warning"></i>
                        <h5 class="card-title">Manage Doctors</h5>
                        <p class="card-text">Approve, edit, or remove doctors and specialties.</p>
                        <a href="{{ route('admin.doctors') }}" class="btn btn-warning mt-2">Go to Doctor Management</a>
                    </div>
                </div>
            </div>
            <!-- Announcements -->
            <div class="col-md-6 mb-4">
                <div class="card-custom animate h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-bullhorn fa-3x mb-3 text-danger"></i>
                        <h5 class="card-title">Announcements</h5>
                        <p class="card-text">Send notifications or announcements to users.</p>
                        <a href="{{ route('admin.announcements') }}" class="btn btn-danger mt-2">Go to Announcements</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    @push('body-class')
    bg-dashboard
    @endpush
</body>
</html>
