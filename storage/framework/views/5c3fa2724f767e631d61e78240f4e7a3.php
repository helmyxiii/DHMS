<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="ulaOiJcDFdToUWvjD95wuk2lOkK3EuuWdmevyZgf">
    
    <title>Doctor Features - Zawar</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="http://localhost:8000/css/patient.css">
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
                        <a class="nav-link" href="http://localhost:8000/doctor/dashboard">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="http://localhost:8000/profile">
                            <i class="fas fa-user me-1"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="http://localhost:8000/logout" class="d-inline">
                            <input type="hidden" name="_token" value="ulaOiJcDFdToUWvjD95wuk2lOkK3EuuWdmevyZgf" autocomplete="off">
                            <button type="submit" class="nav-link border-0 bg-transparent">
                                <i class="fas fa-sign-out-alt me-1"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <div class="container py-5">
            <header class="text-center text-white mb-5">
                <h1 class="fw-bold">Doctor Features</h1>
                <p class="lead">Access all your tools and management features in one place.</p>
            </header>
            <h2 class="section-title text-white text-center mb-4">What Can You Do?</h2>
            <div class="row mb-4">
                <!-- View Appointments -->
                <div class="col-md-6 mb-4">
                    <div class="card-custom animate h-100 text-center">
                        <div class="card-body">
                            <i class="fas fa-calendar-check fa-3x mb-3 text-primary"></i>
                            <h5 class="card-title text-white">View Appointments</h5>
                            <p class="card-text text-white">Check and manage your upcoming appointments.</p>
                            <a href="http://localhost:8000/doctor/appointments" class="btn btn-primary mt-2">Go to Appointments</a>
                        </div>
                    </div>
                </div>
                <!-- Patient Records -->
                <div class="col-md-6 mb-4">
                    <div class="card-custom animate h-100 text-center">
                        <div class="card-body">
                            <i class="fas fa-notes-medical fa-3x mb-3 text-success"></i>
                            <h5 class="card-title text-white">Patient Records</h5>
                            <p class="card-text text-white">Access and update your patients' medical records.</p>
                            <a href="http://localhost:8000/doctor/medical-records" class="btn btn-success mt-2">Go to Patient Records</a>
                        </div>
                    </div>
                </div>
                <!-- Schedule Availability -->
                <div class="col-md-6 mb-4">
                    <div class="card-custom animate h-100 text-center">
                        <div class="card-body">
                            <i class="fas fa-clock fa-3x mb-3 text-info"></i>
                            <h5 class="card-title text-white">Schedule Availability</h5>
                            <p class="card-text text-white">Set your availability for patient consultations.</p>
                            <a href="http://localhost:8000/doctor/schedules" class="btn btn-info mt-2">Go to Schedule</a>
                        </div>
                    </div>
                </div>
                <!-- Reports -->
                <div class="col-md-6 mb-4">
                    <div class="card-custom animate h-100 text-center">
                        <div class="card-body">
                            <i class="fas fa-file-medical fa-3x mb-3 text-danger"></i>
                            <h5 class="card-title text-white">Reports</h5>
                            <p class="card-text text-white">View and generate reports.</p>
                            <a href="http://localhost:8000/doctor/reports" class="btn btn-danger mt-2">Go to Reports</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container text-center">
            <span class="text-muted">&copy; 2025 Zawar Hospital Management System. All rights reserved.</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> <?php /**PATH C:\xampp\htdocs\dhms\resources\views/doctor/features.blade.php ENDPATH**/ ?>