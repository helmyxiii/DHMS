<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Appointment - Cardiologist - Zawar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/patient.css') }}">
    <style>body.bg-dashboard {background: linear-gradient(to right, rgba(47, 102, 127, 0.7), rgba(0, 198, 255, 0.7)), url('/images/background.webp') no-repeat center center fixed;background-size: cover;min-height: 100vh;} .card-custom {background: rgba(0, 48, 80, 0.85);color: #ffffff;border: none;border-radius: 1rem;padding: 1.5rem;margin-bottom: 1.5rem;box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);transition: transform 0.5s ease, opacity 0.5s ease;opacity: 0;transform: translateY(40px);} .card-custom.animate {opacity: 1;transform: translateY(0);} h2.section-title {color: #fff;margin-top: 2rem;margin-bottom: 1rem;} .card-body {background: rgba(255, 255, 255, 0.82);border-radius: 1.25rem;box-shadow: 0 2px 12px 0 rgba(37, 99, 235, 0.07);padding: 2rem 1.5rem;margin-bottom: 1rem;transition: box-shadow 0.2s;} .card:hover .card-body {box-shadow: 0 6px 24px 0 rgba(37, 99, 235, 0.13);} @keyframes fadeInCard {from { opacity: 0; transform: translateY(40px); }to { opacity: 1; transform: none; }}</style>
</head>
<body class="bg-dashboard">
    @include('appointments.partials.navbar')
    <header class="text-center text-white py-5">
        <div class="container">
            <h1 class="fw-bold">Create a New Cardiologist Appointment</h1>
            <p class="lead">Fill in the details below to schedule your appointment with a Cardiologist.</p>
        </div>
    </header>
    <main class="container py-5">
        <div class="dashboard-bg text-white">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <h3 class="text-white mb-4">Appointment Details</h3>
                    <form method="POST" action="{{ route('appointments.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="doctor_name" class="form-label text-white">Doctor Name</label>
                            <input type="text" class="form-control" id="doctor_name" name="doctor_name" value="Cardiologist" readonly>
                            <input type="hidden" name="doctor_name" value="Cardiologist">
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
    <footer class="bg-dark text-white text-center py-3">
        <p class="mb-0">&copy; {{ date('Y') }} Zawar. All rights reserved.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>document.addEventListener("DOMContentLoaded", () => {const cards = document.querySelectorAll(".card-custom");const observer = new IntersectionObserver((entries) => {entries.forEach(entry => {if (entry.isIntersecting) {entry.target.classList.add("animate");observer.unobserve(entry.target);}});}, { threshold: 0.1 });cards.forEach(card => observer.observe(card));});</script>
</body>
</html> 