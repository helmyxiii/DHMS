@extends('layouts.app')

@section('title', 'Medical Record Details')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/medical-record.css') }}">
    <style>
        body.bg-about {
            background: linear-gradient(to right, #2f667f, #00c6ff);
            min-height: 100vh;
        }
        .navbar-brand img {
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .full-height-center {
            min-height: calc(100vh - 120px); /* Adjust for navbar and footer */
            display: flex;
            align-items: center;
            justify-content: center;
        }
        @media (max-width: 991.98px) {
            .full-height-center {
                min-height: unset;
                display: block;
            }
        }
        .blurred-card {
            background: rgba(220, 230, 241, 0.85); /* Soft blue-gray, high transparency */
            color: #222;
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(200, 220, 240, 0.18);
        }
        .blurred-card .card-header {
            background: rgba(47, 102, 127, 0.15);
            color: #2563eb;
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
        }
        footer.footer {
            background: linear-gradient(90deg, #2f667f, #00c6ff) !important;
            color: #fff !important;
        }
    </style>
@endsection

@section('body-class', 'bg-about')

@section('content')
<div class="container-fluid full-height-center">
    <div class="row justify-content-center w-100">
        <div class="col-lg-10 col-xl-9">
            <div class="card blurred-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Medical Record Details</h5>
                    <div>
                        @if(auth()->user()->role === 'doctor' || auth()->user()->role === 'admin')
                            <a href="{{ route('admin.medical-records.edit', ['medical_record' => $medicalRecord->id]) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil"></i> Edit Record
                            </a>
                        @endif
                        <a href="{{ route(auth()->user()->role . '.medical-records.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">

                    {{-- Doctor & Patient Info --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Doctor Information</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="mb-1">
                                        {{ $medicalRecord->doctor && $medicalRecord->doctor->user ? $medicalRecord->doctor->user->name : 'No doctor assigned' }}
                                    </h6>
                                    <p class="mb-0 text-muted">
                                        {{ $medicalRecord->doctor && $medicalRecord->doctor->specialty ? $medicalRecord->doctor->specialty : 'No specialty' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Patient Information</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="mb-1">
                                        {{ $medicalRecord->patient && $medicalRecord->patient->user ? $medicalRecord->patient->user->name : 'No patient assigned' }}
                                    </h6>
                                    <p class="mb-0 text-muted">
                                        Age: {{ $medicalRecord->patient && $medicalRecord->patient->user && $medicalRecord->patient->user->age ? $medicalRecord->patient->user->age : 'N/A' }} years
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Record Metadata --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Record Information</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-1"><strong>Date:</strong> {{ $medicalRecord->created_at->format('F j, Y') }}</p>
                                    <p class="mb-1"><strong>Last Updated:</strong> {{ $medicalRecord->updated_at->format('F j, Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Related Appointment</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    @if($medicalRecord->appointment)
                                        <p class="mb-1"><strong>Date:</strong> {{ $medicalRecord->appointment->appointment_date->format('F j, Y H:i') }}</p>
                                        <p class="mb-0"><strong>Type:</strong> {{ ucfirst($medicalRecord->appointment->type) }}</p>
                                    @else
                                        <p class="mb-0 text-muted">No related appointment</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Medical Info --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Medical Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Symptoms</h6>
                                <p class="mb-0">{{ $medicalRecord->symptoms }}</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Diagnosis</h6>
                                <p class="mb-0">{{ $medicalRecord->diagnosis }}</p>
                            </div>
                            @if($medicalRecord->notes)
                                <div>
                                    <h6 class="text-muted mb-2">Additional Notes</h6>
                                    <p class="mb-0">{{ $medicalRecord->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Treatments --}}
                    @if($medicalRecord->treatments->count() > 0)
                        <div class="card mb-4">
                            <div class="card-header"><h6 class="mb-0">Treatments</h6></div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Treatment</th>
                                                <th>Status</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($medicalRecord->treatments as $treatment)
                                                <tr>
                                                    <td>{{ $treatment->treatment_name }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $treatment->status === 'completed' ? 'success' : 
                                                                                ($treatment->status === 'in_progress' ? 'primary' : 'warning') }}">
                                                            {{ ucfirst(str_replace('_', ' ', $treatment->status)) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $treatment->start_date->format('M j, Y') }}</td>
                                                    <td>{{ $treatment->end_date ? $treatment->end_date->format('M j, Y') : 'Ongoing' }}</td>
                                                    <td>{{ $treatment->description }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Prescriptions --}}
                    @if($medicalRecord->prescriptions->count() > 0)
                        <div class="card mb-4">
                            <div class="card-header"><h6 class="mb-0">Prescriptions</h6></div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Medicine</th>
                                                <th>Dosage</th>
                                                <th>Frequency</th>
                                                <th>Duration</th>
                                                <th>Instructions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($medicalRecord->prescriptions as $prescription)
                                                <tr>
                                                    <td>{{ $prescription->medicine_name }}</td>
                                                    <td>{{ $prescription->dosage }}</td>
                                                    <td>{{ $prescription->frequency }}</td>
                                                    <td>{{ $prescription->duration }}</td>
                                                    <td>{{ $prescription->instructions }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Delete Button --}}
                    @if(auth()->user()->role === 'doctor' || auth()->user()->role === 'admin')
                        <div class="d-flex justify-content-between mt-4">
                            <form action="{{ route('medical-records.destroy', $medicalRecord) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this medical record?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> Delete Record
                                </button>
                            </form>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
