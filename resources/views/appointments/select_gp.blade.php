@extends('layouts.app')
@section('title', 'Select General Practitioner')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Select a General Practitioner</h2>
    <div class="row">
        @foreach($doctors as $doctor)
            @if($doctor->specialty === 'gp')
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        @php
                            $rating = number_format(rand(35, 50) / 10, 1);
                        @endphp
                        <div class="mb-2">
                            <span class="badge bg-warning text-dark">
                                ★ {{ $rating }} / 5.0
                            </span>
                        </div>
                        <h5 class="card-title">{{ $doctor->name }}</h5>
                        <p class="card-text">{{ $doctor->bio ?? 'Experienced General Practitioner' }}</p>
                        <a href="{{ route('appointments.createForDoctor', $doctor->id) }}" class="btn btn-primary">Book Appointment</a>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>
</div>
@endsection 