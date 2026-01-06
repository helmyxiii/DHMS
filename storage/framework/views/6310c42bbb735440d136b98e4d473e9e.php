<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Medical Records - Zawar</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/patient.css')); ?>">

    <style>
 body.bg-dashboard {
            background: linear-gradient(to right, rgba(47, 102, 127, 0.7), rgba(0, 198, 255, 0.7)), url('/images/background.webp') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }

        .dashboard-bg {
            background: linear-gradient(rgba(255,255,255,0.78), rgba(255,255,255,0.78)), url('<?php echo e(asset('images/medical-hexagons.jpg')); ?>') center center / cover no-repeat;
            border-radius: 2.5rem;
            box-shadow: 0 12px 48px rgba(37, 99, 235, 0.13), 0 1.5px 8px rgba(0, 0, 0, 0.06);
            border: 2px solid transparent;
            border-image: linear-gradient(90deg, #60a5fa 0%, #2563eb 100%);
            border-image-slice: 1;
            padding: 3rem 2.5rem;
            margin-top: 4rem;
            margin-bottom: 4rem;
            backdrop-filter: blur(12px);
            animation: fadeInCard 0.8s ease;
        }

        @keyframes fadeInCard {
            from { opacity: 0; transform: translateY(40px); }
            to   { opacity: 1; transform: none; }
        }

        .text-light-muted {
            color: #1e3a8a;
            opacity: 0.85;
        }

        .navbar-nav .btn {
            padding: 0.4rem 0.9rem;
            font-size: 0.875rem;
        }

        .navbar .dropdown-menu {
            left: auto !important;
            right: 0;
            top: 100%;
            transform: none !important;
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
                    <li class="nav-item"><a class="nav-link" href="<?php echo e(url('/')); ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo e(url('/about')); ?>">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo e(route('appointments.index')); ?>">Appointments</a></li>
                    <li class="nav-item"><a class="nav-link active" href="<?php echo e(route('patient.medical-records.index')); ?>">Medical Records</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo e(route('health-tips.index')); ?>">Health Tips</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo e(route('contact')); ?>">Contact</a></li>
                    <?php if(auth()->guard()->check()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle fa-lg me-1"></i> <?php echo e(Auth::user()->name); ?>

                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item d-flex align-items-center" href="<?php echo e(url('/profile')); ?>">
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
    <header class="bg-patient-header text-center">
        <div class="container">
            <h1>Medical Records</h1>
            <p>View your past diagnoses, treatments, and prescriptions</p>
        </div>
    </header>

    <!-- Records Section -->
    <main class="container py-5">
        <div class="dashboard-bg">
            <div class="text-center py-5">
                <h3 class="text-light-muted">No medical records found</h3>
                <p class="text-light-muted">Your medical records will appear here once added by your doctor.</p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p class="mb-0">&copy; <?php echo e(date('Y')); ?> Zawar. All rights reserved.</p>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\dhms\resources\views/patient/medical-records/index.blade.php ENDPATH**/ ?>