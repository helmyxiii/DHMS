<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Patient Dashboard</title>

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom CSS -->
    <!-- <link rel="stylesheet" href="{{ asset('css/patient.css') }}"> -->
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
                    <li class="nav-item"><a class="nav-link active" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/about') }}">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('appointments.index') }}">Appointments</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('patient.medical-records.index') }}">Medical Records</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('health-tips.index') }}">Health Tips</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="bg-patient-header text-center">
        <div class="container">
            <h1>Manage your appointments, records, and more.</h1>
        </div>
    </header>

    <!-- Dashboard -->
    <main class="container py-5">
        <div class="row g-4">
            <!-- My Appointments -->
            <div class="col-md-4">
                <div class="dashboard-card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-calendar-check fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">My Appointments</h5>
                        <p class="card-text">View and manage your upcoming appointments.</p>
                        <a href="{{ route('appointments.index') }}" class="btn btn-primary">View Appointments</a>
                    </div>
                </div>
            </div>
            <!-- Medical Records -->
            <div class="col-md-4">
                <div class="dashboard-card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-file-medical fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Medical Records</h5>
                        <p class="card-text">Access your medical history and prescriptions.</p>
                        <a href="{{ route('patient.medical-records.index') }}" class="btn btn-primary">View Records</a>
                    </div>
                </div>
            </div>
            <!-- Health Tips -->
            <div class="col-md-4">
                <div class="dashboard-card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-heartbeat fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Health Tips</h5>
                        <p class="card-text">Get personalized health advice and tips.</p>
                        <a href="{{ route('health-tips.index') }}" class="btn btn-primary">Explore Tips</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Specialties Section -->
    <section class="container pb-5">
        <h2 class="text-center mb-4">Find a Specialist</h2>
        <div class="row g-4 justify-content-center">
            <div class="col-md-3">
                <div class="dashboard-card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-tooth fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Dentists</h5>
                        <p class="card-text">Find the best dentists near you.</p>
                        <a href="{{ route('appointments.selectDoctor', ['specialty' => 'dentist']) }}" class="btn btn-outline-primary">Find Dentists</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-allergies fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Dermatologists</h5>
                        <p class="card-text">Skin care specialists available now.</p>
                        <a href="{{ route('appointments.selectDoctor', ['specialty' => 'dermatologist']) }}" class="btn btn-outline-primary">Find Dermatologists</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-heartbeat fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Cardiologists</h5>
                        <p class="card-text">Book the best heart specialists.</p>
                        <a href="{{ route('appointments.selectDoctor', ['specialty' => 'cardiologist']) }}" class="btn btn-outline-primary">Find Cardiologists</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-user-md fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">General Practitioners</h5>
                        <p class="card-text">Consult with a GP now.</p>
                        <a href="{{ route('appointments.selectDoctor', ['specialty' => 'gp']) }}" class="btn btn-outline-primary">Find GPs</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p class="mb-0">&copy; {{ date('Y') }} Zawar. All rights reserved.</p>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
