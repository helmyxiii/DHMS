@extends('layouts.app')
@section('title', 'Select Dermatologist')

@section('content')
<!-- Header -->
<header class="text-center text-white py-5">
    <div class="container">
        <h1 class="fw-bold">Find a Dermatologist</h1>
        <p class="lead">Choose from our trusted skin care specialists and book your appointment easily.</p>
    </div>
</header>
<main class="container mb-5">
    <div class="row">
        @php $colors = ['primary', 'info', 'success', 'danger', 'warning', 'secondary']; @endphp
        @forelse($doctors as $doctor)
            @if($doctor->specialty === 'dermatologist')
            <div class="col-md-4 mb-4">
                <div class="card-custom animate h-100">
                    <div class="card-body text-white">
                        @php
                            $rating = number_format(rand(35, 50) / 10, 1);
                            $color = $colors[$loop->index % count($colors)];
                        @endphp
                        <div class="mb-2">
                            <span class="badge bg-warning text-dark">
                                ★ {{ $rating }} / 5.0
                            </span>
                        </div>
                        <h5 class="card-title text-{{ $color }}">{{ $doctor->name }}</h5>
                        <p class="card-text">{{ $doctor->bio ?? 'Experienced Dermatologist' }}</p>
                        <a href="{{ route('appointments.createForDoctor', $doctor->id) }}" class="btn btn-{{ $color }} mt-2">Book Appointment</a>
                    </div>
                </div>
            </div>
            @endif
        @empty
            <div class="col-12">
                <div class="card-custom animate text-center p-4">
                    <h5 class="text-white">No dermatologists found at this time.</h5>
                </div>
            </div>
        @endforelse
    </div>
</main>
@endsection

@push('body-class')
bg-dashboard
@endpush 