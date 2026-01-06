<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Create Appointment - Zawar</title>

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('css/patient.css')); ?>">
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
            <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
                <img src="<?php echo e(asset('images/zawar.jpeg')); ?>" alt="Zawar" height="40"> Zawar
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('home') ? 'active' : ''); ?>" href="<?php echo e(url('/')); ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('about') ? 'active' : ''); ?>" href="<?php echo e(url('/about')); ?>">About</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('appointments.*') ? 'active' : ''); ?>" href="<?php echo e(route('appointments.index')); ?>">Appointments</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('patient.medical-records.*') ? 'active' : ''); ?>" href="<?php echo e(route('patient.medical-records.index')); ?>">Medical Records</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('health-tips.*') ? 'active' : ''); ?>" href="<?php echo e(route('health-tips.index')); ?>">Health Tips</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('contact') ? 'active' : ''); ?>" href="<?php echo e(route('contact')); ?>">Contact</a></li>
                    <?php if(auth()->guard()->guest()): ?>
                        <li class="nav-item">
                            <a class="btn btn-outline-light btn-sm ms-2" href="<?php echo e(route('login')); ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-light btn-sm ms-2" href="<?php echo e(route('register')); ?>">Sign Up</a>
                        </li>
                    <?php endif; ?>
                    <?php if(auth()->guard()->check()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle fa-lg me-1"></i> <?php echo e(Auth::user()->name); ?>

                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item d-flex align-items-center" href="<?php echo e(route('profile.edit')); ?>">
                                    <i class="fas fa-user-edit me-2 text-primary"></i> <span>Profile</span>
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button class="dropdown-item d-flex align-items-center text-danger" type="submit">
                                            <i class="fas fa-sign-out-alt me-2"></i> <span>Logout</span>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <header class="text-center text-white py-5">
        <div class="container">
            <h1 class="fw-bold">Create a New Appointment</h1>
            <p class="lead">Fill in the details below to schedule your appointment.</p>
                </div>
    </header>

    <!-- Appointment Creation Section -->
    <main class="container py-5">
        <div class="dashboard-bg text-white">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <h3 class="text-white mb-4">Appointment Details</h3>
                    <form method="POST" action="<?php echo e(route('appointments.store')); ?>">
                        <?php echo csrf_field(); ?>
                        <?php if(isset($doctor)): ?>
                            <input type="hidden" name="doctor_id" value="<?php echo e($doctor->id); ?>">
                        <?php endif; ?>
                        <div class="mb-3">
                            <label for="doctor_name" class="form-label text-white">Doctor Name</label>
                            <input type="text" class="form-control" id="doctor_name" name="doctor_name" value="<?php echo e(isset($doctor) ? $doctor->name : ''); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="appointment_date" class="form-label text-white">Appointment Date</label>
                            <input type="date" class="form-control" id="appointment_date" name="appointment_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="time_slot" class="form-label text-white">Time Slot</label>
                            <input type="time" class="form-control" id="time_slot" name="time_slot" required>
                        </div>
                        <div class="mb-3">
                            <label for="reason" class="form-label text-white">Reason for Visit</label>
                            <textarea class="form-control" id="reason" name="reason" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Book Appointment</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p class="mb-0">&copy; <?php echo e(date('Y')); ?> Zawar. All rights reserved.</p>
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
</html><?php /**PATH C:\xampp\htdocs\dhms\resources\views/appointments/create.blade.php ENDPATH**/ ?>