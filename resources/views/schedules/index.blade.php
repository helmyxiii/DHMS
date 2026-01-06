@extends('layouts.app')

@section('title', 'Doctor Schedules')

@section('content')
<div class="container py-5">
    <header class="text-center text-white mb-5">
        <h1 class="fw-bold">My Schedules</h1>
        <p class="lead">Manage your availability for patient consultations.</p>
    </header>
    <div class="text-end mb-4">
        <a href="{{ route('doctor.schedules.create') }}" class="btn btn-primary">Add New Schedule</a>
    </div>
    <div class="card-custom animate">
        <div class="card-body">
            <table class="table table-hover table-dark table-bordered mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Available</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->date }}</td>
                            <td>{{ $schedule->start_time }}</td>
                            <td>{{ $schedule->end_time }}</td>
                            <td>
                                <span class="badge bg-{{ $schedule->is_available ? 'success' : 'secondary' }}">
                                    {{ $schedule->is_available ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('doctor.schedules.edit', $schedule) }}" class="btn btn-sm btn-info">Edit</a>
                                <form action="{{ route('doctor.schedules.destroy', $schedule) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this schedule?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-white">No schedules found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-2">
                {{ $schedules->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
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
h1.fw-bold, .lead, .text-white, .table-dark th, .table-dark td {
    color: #fff !important;
}
.table-dark {
    background-color: transparent;
}
</style>
@endpush

@push('body-class')
bg-dashboard
@endpush 