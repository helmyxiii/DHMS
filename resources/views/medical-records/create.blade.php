@extends('layouts.app')

@section('title', 'New Medical Record')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/medical-record.css') }}">
    <style>
        body.bg-about {
            background: linear-gradient(to right, #2f667f, #00c6ff);
            min-height: 100vh;
        }
        .soft-card {
            background: rgba(220, 230, 241, 0.85);
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            border: 1px solid rgba(200, 220, 240, 0.18);
        }
        .soft-card .card-header {
            background: rgba(47, 102, 127, 0.15);
            color: #2563eb;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
        }
    </style>
@endsection

@section('body-class', 'bg-about')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <div class="card soft-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Create Medical Record</h5>
                </div>
                <div class="card-body">
                    @if(isset($appointment))
                        <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Creating medical record for appointment with 
                            <strong>{{ $appointment->patient->user->name }}</strong> on 
                            {{ $appointment->appointment_date->format('M d, Y h:i A') }}
                        </div>
                    @else
                        @if(auth()->user()->isAdmin())
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="doctor_id" class="form-label">Doctor</label>
                                    <select name="doctor_id" id="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror" required>
                                        <option value="">Select doctor...</option>
                                        @foreach($doctors as $doctor)
                                            <option value="{{ $doctor->id }}" 
                                                {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                                {{ $doctor->user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('doctor_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="patient_id" class="form-label">Patient</label>
                                    <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                                        <option value="">Select patient...</option>
                                        @foreach($patients as $patient)
                                            <option value="{{ $patient->id }}" 
                                                {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                                {{ $patient->user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('patient_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @elseif(auth()->user()->isDoctor())
                            <div class="mb-3">
                                <label for="patient_id" class="form-label">Patient</label>
                                <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                                    <option value="">Select patient...</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" 
                                            {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('patient_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                    @endif

                    <div class="mb-3">
                        <label for="symptoms" class="form-label">Symptoms</label>
                        <textarea class="form-control @error('symptoms') is-invalid @enderror" 
                                  id="symptoms" name="symptoms" rows="3" required>{{ old('symptoms') }}</textarea>
                        @error('symptoms')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="diagnosis" class="form-label">Diagnosis</label>
                        <textarea class="form-control @error('diagnosis') is-invalid @enderror" 
                                  id="diagnosis" name="diagnosis" rows="3" required>{{ old('diagnosis') }}</textarea>
                        @error('diagnosis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="record_type" class="form-label">Record Type</label>
                        <input type="text" class="form-control @error('record_type') is-invalid @enderror" 
                               id="record_type" name="record_type" value="{{ old('record_type') }}" required>
                        @error('record_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="record_date" class="form-label">Record Date</label>
                        <input type="date" class="form-control @error('record_date') is-invalid @enderror"
                               id="record_date" name="record_date" value="{{ old('record_date', date('Y-m-d')) }}" required>
                        @error('record_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Additional Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Treatments</h6>
                            <button type="button" class="btn btn-sm btn-primary" id="addTreatment">
                                <i class="bi bi-plus-lg"></i> Add Treatment
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="treatments-container">
                                <!-- Treatment fields will be added here dynamically -->
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Prescriptions</h6>
                            <button type="button" class="btn btn-sm btn-primary" id="addPrescription">
                                <i class="bi bi-plus-lg"></i> Add Prescription
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="prescriptions-container">
                                <!-- Prescription fields will be added here dynamically -->
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route(auth()->user()->role . '.medical-records.index') }}" 
                           class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Medical Record</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Treatment Template -->
<template id="treatment-template">
    <div class="treatment-item border rounded p-3 mb-3">
        <div class="d-flex justify-content-between mb-3">
            <h6 class="mb-0">Treatment</h6>
            <button type="button" class="btn btn-sm btn-danger remove-treatment">
                <i class="bi bi-trash"></i> Delete
            </button>
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Treatment Name</label>
                <input type="text" class="form-control" name="treatments[INDEX][treatment_name]" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select class="form-select" name="treatments[INDEX][status]" required>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Start Date</label>
                <input type="date" class="form-control" name="treatments[INDEX][start_date]" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">End Date</label>
                <input type="date" class="form-control" name="treatments[INDEX][end_date]">
            </div>
            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="treatments[INDEX][description]" rows="2" required></textarea>
            </div>
        </div>
    </div>
</template>

<!-- Prescription Template -->
<template id="prescription-template">
    <div class="prescription-item border rounded p-3 mb-3">
        <div class="d-flex justify-content-between mb-3">
            <h6 class="mb-0">Prescription</h6>
            <button type="button" class="btn btn-sm btn-danger remove-prescription">
                <i class="bi bi-trash"></i> Delete
            </button>
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Medicine Name</label>
                <input type="text" class="form-control" name="prescriptions[INDEX][medicine_name]" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Dosage</label>
                <input type="text" class="form-control" name="prescriptions[INDEX][dosage]" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Frequency</label>
                <input type="text" class="form-control" name="prescriptions[INDEX][frequency]" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Duration</label>
                <input type="text" class="form-control" name="prescriptions[INDEX][duration]" required>
            </div>
            <div class="col-12">
                <label class="form-label">Instructions</label>
                <textarea class="form-control" name="prescriptions[INDEX][instructions]" rows="2" required></textarea>
            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
    $(document).ready(function() {
        function initSelect2() {
            $('#doctor_id, #patient_id').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select...',
                allowClear: true
            });
        }
        initSelect2();
        let treatmentIndex = 0;
        let prescriptionIndex = 0;
        // Add Treatment
        $('#addTreatment').on('click', function() {
            const template = document.getElementById('treatment-template');
            const clone = template.content.cloneNode(true);
            let html = $('<div>').append(clone).html().replace(/INDEX/g, treatmentIndex);
            $('#treatments-container').append(html);
            treatmentIndex++;
        });
        // Remove Treatment
        $(document).on('click', '.remove-treatment', function() {
            $(this).closest('.treatment-item').remove();
        });
        // Add Prescription
        $('#addPrescription').on('click', function() {
            const template = document.getElementById('prescription-template');
            const clone = template.content.cloneNode(true);
            let html = $('<div>').append(clone).html().replace(/INDEX/g, prescriptionIndex);
            $('#prescriptions-container').append(html);
            prescriptionIndex++;
        });
        // Remove Prescription
        $(document).on('click', '.remove-prescription', function() {
            $(this).closest('.prescription-item').remove();
        });
        // Form validation
        $('#medicalRecordForm').on('submit', function(e) {
            const treatments = $('.treatment-item').length;
            const prescriptions = $('.prescription-item').length;
            if (treatments === 0 && prescriptions === 0) {
                e.preventDefault();
                alert('Please add at least one treatment or prescription.');
                return false;
            }
        });
    });
</script>
@endpush 