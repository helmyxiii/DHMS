<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Health Tips - Zawar</title>

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/patient.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/health-tips.css')); ?>">

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
                        <input type="text" name="search" class="form-control" placeholder="Search health tips..." value="<?php echo e(request()->get('search')); ?>">
                        <select name="category" class="form-select" style="max-width:200px;">
                            <option value="">All Categories</option>
                            <option value="Tips for Sleep" <?php echo e(request('category') === 'Tips for Sleep' ? 'selected' : ''); ?>>Tips for Sleep</option>
                            <option value="Tips for Nutrition" <?php echo e(request('category') === 'Tips for Nutrition' ? 'selected' : ''); ?>>Tips for Nutrition</option>
                            <option value="Tips for Heart Patients" <?php echo e(request('category') === 'Tips for Heart Patients' ? 'selected' : ''); ?>>Tips for Heart Patients</option>
                            <option value="Tips for Sugar Patients" <?php echo e(request('category') === 'Tips for Sugar Patients' ? 'selected' : ''); ?>>Tips for Sugar Patients</option>
                            <option value="Routine Care" <?php echo e(request('category') === 'Routine Care' ? 'selected' : ''); ?>>Routine Care</option>
                        </select>
                        <button class="btn btn-primary" type="submit">Filter</button>
                    </form>
                </div>
            </div>

            <div class="row">
                <?php $__currentLoopData = $healthTips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-4 mb-4">
                        <div class="health-tip-card animate">
                            <div class="health-tip-title"><?php echo e($tip->title); ?></div>
                            <div class="health-tip-content"><?php echo e(Str::limit($tip->content, 100)); ?></div>
                            <button class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#tipModal<?php echo e($tip->id); ?>">Read More</button>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="tipModal<?php echo e($tip->id); ?>" tabindex="-1" aria-labelledby="tipModalLabel<?php echo e($tip->id); ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="tipModalLabel<?php echo e($tip->id); ?>"><?php echo e($tip->title); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="health-tip-content"><?php echo e($tip->content); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php /**PATH C:\xampp\htdocs\dhms\resources\views/health-tips/index.blade.php ENDPATH**/ ?>