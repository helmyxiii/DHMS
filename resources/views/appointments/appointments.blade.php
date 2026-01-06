<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments - Zawar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/appoint.css') }}">
</head>
<body class="bg-appointments">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light shadow">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/zawar.jpeg') }}" alt="Zawar"> Zawar
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/about') }}">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/appointments') }}">Appointments</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url('/admin/dashboard') }}">Admin</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/doctor/dashboard') }}">Doctor</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/patient/dashboard') }}">Patient</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/login') }}">Sign In</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/register') }}">Sign Up</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/contact') }}">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>
<!-- Hero Section -->
<header class="bg-appointments-header">
    <div class="container">
        <h1>Manage Your Appointments</h1>
        <p>Book, view, and manage your appointments with ease.</p>
    </div>
</header>

<!-- Appointment Form -->
<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h2 class="text-center mb-0">Schedule an Appointment</h2>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @php
                        $user = auth()->user();
                        $route = $user->isDoctor() ? route('doctor.appointments.store') : route('patient.appointments.store');
                    @endphp

                    <form action="{{ $route }}" method="POST" id="appointmentForm" class="sign-form" data-validate>
                        @csrf
                        
                        <div class="mb-3">
                            <label for="doctor" class="form-label">Select Doctor</label>
                            <select id="doctor" name="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror" required>
                                <option value="">Choose a doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }} - {{ $doctor->specialty?->name ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="appointment_date" class="form-label">Select Date</label>
                            <input type="date" 
                                   id="appointment_date" 
                                   name="appointment_date" 
                                   class="form-control @error('appointment_date') is-invalid @enderror"
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ old('appointment_date') }}"
                                   required>
                            @error('appointment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="time_slot" class="form-label">Available Time Slots</label>
                            <select id="time_slot" 
                                    name="time_slot" 
                                    class="form-select @error('time_slot') is-invalid @enderror"
                                    required>
                                <option value="">Select a time slot</option>
                                <!-- Time slots will be populated via JavaScript -->
                            </select>
                            @error('time_slot')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason for Visit</label>
                            <textarea id="reason" 
                                      name="reason" 
                                      class="form-control @error('reason') is-invalid @enderror"
                                      rows="3"
                                      required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calendar-check me-2"></i>Book Appointment
                            </button>
                            <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-calendar-alt me-2"></i>View My Appointments
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Upcoming Appointments -->
            @if(isset($upcomingAppointments) && $upcomingAppointments->count() > 0)
            <div class="card shadow mt-4">
                <div class="card-header bg-info text-white">
                    <h3 class="text-center mb-0">Your Upcoming Appointments</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Doctor</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingAppointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                                    <td>{{ $appointment->time_slot }}</td>
                                    <td>{{ $appointment->doctor->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $appointment->status === 'confirmed' ? 'success' : ($appointment->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            @php
                                                $user = auth()->user();
                                                $showRoute = $user->isDoctor() ? route('doctor.appointments.show', $appointment) : route('patient.appointments.show', $appointment);
                                                $cancelRoute = $user->isDoctor() ? route('doctor.appointments.destroy', $appointment) : route('patient.appointments.destroy', $appointment);
                                            @endphp
                                            <a href="{{ $showRoute }}" class="btn btn-sm btn-info" data-tooltip="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($appointment->status === 'pending')
                                            <form action="{{ $cancelRoute }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" data-tooltip="Cancel Appointment">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script src="{{ asset('js/appointments.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const doctorSelect = document.getElementById('doctor');
    const dateInput = document.getElementById('appointment_date');
    const timeSlotSelect = document.getElementById('time_slot');

    // Function to fetch available time slots
    async function fetchTimeSlots(doctorId, date) {
        try {
            const response = await fetch(`/api/doctors/${doctorId}/available-slots?date=${date}`);
            const data = await response.json();
            
            // Clear existing options
            timeSlotSelect.innerHTML = '<option value="">Select a time slot</option>';
            
            // Add new options
            data.slots.forEach(slot => {
                const option = document.createElement('option');
                option.value = slot;
                option.textContent = slot;
                timeSlotSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error fetching time slots:', error);
        }
    }

    // Event listeners for dynamic time slot updates
    doctorSelect.addEventListener('change', function() {
        if (this.value && dateInput.value) {
            fetchTimeSlots(this.value, dateInput.value);
        }
    });

    dateInput.addEventListener('change', function() {
        if (doctorSelect.value && this.value) {
            fetchTimeSlots(doctorSelect.value, this.value);
        }
    });

    // Form validation
    const form = document.getElementById('appointmentForm');
    form.addEventListener('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        this.classList.add('was-validated');
    });
});
</script>
@endsection