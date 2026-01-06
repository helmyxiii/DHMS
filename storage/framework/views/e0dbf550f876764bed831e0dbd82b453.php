<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select a Dentist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, rgba(47, 102, 127, 0.7), rgba(0, 198, 255, 0.7)), url('/images/background.webp') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            color: #ffffff;
        }
        .card {
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
        .card.animate {
            opacity: 1;
            transform: translateY(0);
        }
        h1, .lead {
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Select a Dentist</h1>
        <p class="lead">Find the best dentists near you.</p>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card animate">
                    <div class="card-body">
                        <h5 class="card-title">Dr. John Doe</h5>
                        <p class="card-text">General Dentist</p>
                        <a href="<?php echo e(route('appointments.create', ['doctor' => 'Dr. John Doe'])); ?>" class="btn btn-primary">Make Appointment</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card animate">
                    <div class="card-body">
                        <h5 class="card-title">Dr. Jane Smith</h5>
                        <p class="card-text">Orthodontist</p>
                        <a href="<?php echo e(route('appointments.create', ['doctor' => 'Dr. Jane Smith'])); ?>" class="btn btn-primary">Make Appointment</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card animate">
                    <div class="card-body">
                        <h5 class="card-title">Dr. Emily Johnson</h5>
                        <p class="card-text">Pediatric Dentist</p>
                        <a href="<?php echo e(route('appointments.create', ['doctor' => 'Dr. Emily Johnson'])); ?>" class="btn btn-primary">Make Appointment</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const cards = document.querySelectorAll(".card");

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
</html> <?php /**PATH C:\xampp\htdocs\dhms\resources\views/doctors/select.blade.php ENDPATH**/ ?>