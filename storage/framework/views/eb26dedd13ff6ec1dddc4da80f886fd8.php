<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="s7v9Ev8oIwkzW7mV3ApxM1Q0yag8oJtKTRv2uplN">
    <title>Dashboard</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="http://localhost:8000/css/patient.css">
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
                        <a class="nav-link active" href="http://localhost:8000">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="http://localhost:8000/profile">
                            <i class="fas fa-user me-1"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="http://localhost:8000/logout" class="d-inline">
                            <input type="hidden" name="_token" value="s7v9Ev8oIwkzW7mV3ApxM1Q0yag8oJtKTRv2uplN" autocomplete="off">
                            <button type="submit" class="nav-link border-0 bg-transparent">
                                <i class="fas fa-sign-out-alt me-1"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Banner -->
    <header class="text-center text-white py-5">
        <div class="container">
            <h1 class="fw-bold">Welcome to Your Dashboard</h1>
            <p class="lead">Manage your appointments, records, and more.</p>
        </div>
    </header>

    <main class="container py-5">
        <div class="dashboard-bg text-white">
            <div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card-custom animate">
                            <h5>Total Appointments</h5>
                            <p>7</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-custom animate">
                            <h5>Upcoming Appointments</h5>
                            <p>5</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-custom animate">
                            <h5>Medical Records</h5>
                            <p>7</p>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card-custom animate text-center">
                            <div class="card-body">
                                <i class="fas fa-stethoscope fa-3x mb-3 text-info"></i>
                                <h5 class="card-title text-white">Doctor Features</h5>
                                <p class="card-text">Access all your tools and management features in one place.</p>
                                <a href="http://localhost:8000/doctor/features" class="btn btn-info text-white">Go to Doctor Features</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Appointments -->
                <div class="card-custom animate mb-4">
                    <h2 class="mb-3">Upcoming Appointments</h2>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Doctor</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2025-05-17</td>
                                    <td>Dr. Smith</td>
                                    <td><span class="badge bg-danger">Cancelled</span></td>
                                    <td>
                                        <a href="http://localhost:8000/appointments/1" class="btn btn-sm btn-primary">View</a>
                                        <a href="http://localhost:8000/patient/appointments/request-change/1" class="btn btn-sm btn-info">Request Change</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-3">
        <p class="mb-0">&copy; 2025 Zawar. All rights reserved.</p>
    </footer>

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
</html> <?php /**PATH C:\xampp\htdocs\dhms\resources\views/doctor/dashboard.blade.php ENDPATH**/ ?>