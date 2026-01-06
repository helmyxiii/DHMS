@extends('layouts.app')

@section('title', 'Edit Medical Record')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Medical Record</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route(auth()->user()->role . '.medical-records.update', $medicalRecord) }}" method="POST" id="medicalRecordForm">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Doctor</label>
                                <input type="text" class="form-control" value="{{ $medicalRecord->doctor && $medicalRecord->doctor->user ? $medicalRecord->doctor->user->name : 'No doctor assigned' }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Patient</label>
                                <input type="text" class="form-control" value="{{ $medicalRecord->patient && $medicalRecord->patient->user ? $medicalRecord->patient->user->name : 'No patient assigned' }}" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="symptoms" class="form-label">Symptoms</label>
                            <textarea class="form-control @error('symptoms') is-invalid @enderror" 
                                      id="symptoms" name="symptoms" rows="3" required>{{ old('symptoms', $medicalRecord->symptoms) }}</textarea>
                            @error('symptoms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="diagnosis" class="form-label">Diagnosis</label>
                            <textarea class="form-control @error('diagnosis') is-invalid @enderror" 
                                      id="diagnosis" name="diagnosis" rows="3" required>{{ old('diagnosis', $medicalRecord->diagnosis) }}</textarea>
                            @error('diagnosis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="2">{{ old('notes', $medicalRecord->notes) }}</textarea>
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
                                    @foreach($medicalRecord->treatments as $index => $treatment)
                                        <div class="treatment-item border rounded p-3 mb-3">
                                            <div class="d-flex justify-content-between mb-3">
                                                <h6 class="mb-0">Treatment</h6>
                                                <button type="button" class="btn btn-sm btn-danger remove-treatment">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                            <input type="hidden" name="treatments[{{ $index }}][id]" value="{{ $treatment->id }}">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Treatment Name</label>
                                                    <input type="text" class="form-control" 
                                                           name="treatments[{{ $index }}][treatment_name]" 
                                                           value="{{ $treatment->treatment_name }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Status</label>
                                                    <select class="form-select" 
                                                            name="treatments[{{ $index }}][status]" required>
                                                        <option value="pending" {{ $treatment->status == 'pending' ? 'selected' : '' }}>
                                                            Pending
                                                        </option>
                                                        <option value="in_progress" {{ $treatment->status == 'in_progress' ? 'selected' : '' }}>
                                                            In Progress
                                                        </option>
                                                        <option value="completed" {{ $treatment->status == 'completed' ? 'selected' : '' }}>
                                                            Completed
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Start Date</label>
                                                    <input type="date" class="form-control" 
                                                           name="treatments[{{ $index }}][start_date]" 
                                                           value="{{ $treatment->start_date->format('Y-m-d') }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">End Date</label>
                                                    <input type="date" class="form-control" 
                                                           name="treatments[{{ $index }}][end_date]" 
                                                           value="{{ $treatment->end_date ? $treatment->end_date->format('Y-m-d') : '' }}">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">Description</label>
                                                    <textarea class="form-control" 
                                                              name="treatments[{{ $index }}][description]" 
                                                              rows="2" required>{{ $treatment->description }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
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
                                    @foreach($medicalRecord->prescriptions as $index => $prescription)
                                        <div class="prescription-item border rounded p-3 mb-3">
                                            <div class="d-flex justify-content-between mb-3">
                                                <h6 class="mb-0">Prescription</h6>
                                                <button type="button" class="btn btn-sm btn-danger remove-prescription">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                            <input type="hidden" name="prescriptions[{{ $index }}][id]" value="{{ $prescription->id }}">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Medicine Name</label>
                                                    <input type="text" class="form-control" 
                                                           name="prescriptions[{{ $index }}][medicine_name]" 
                                                           value="{{ $prescription->medicine_name }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Dosage</label>
                                                    <input type="text" class="form-control" 
                                                           name="prescriptions[{{ $index }}][dosage]" 
                                                           value="{{ $prescription->dosage }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Frequency</label>
                                                    <input type="text" class="form-control" 
                                                           name="prescriptions[{{ $index }}][frequency]" 
                                                           value="{{ $prescription->frequency }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Duration</label>
                                                    <input type="text" class="form-control" 
                                                           name="prescriptions[{{ $index }}][duration]" 
                                                           value="{{ $prescription->duration }}" required>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">Instructions</label>
                                                    <textarea class="form-control" 
                                                              name="prescriptions[{{ $index }}][instructions]" 
                                                              rows="2" required>{{ $prescription->instructions }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route(auth()->user()->role . '.medical-records.index') }}" 
                               class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Medical Record</button>
                        </div>
                    </form>
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
                <i class="bi bi-trash"></i>
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
                <i class="bi bi-trash"></i>
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
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let treatmentIndex = {{ count($medicalRecord->treatments) }};
        let prescriptionIndex = {{ count($medicalRecord->prescriptions) }};

        // Add Treatment
        $('#addTreatment').click(function() {
            const template = document.getElementById('treatment-template').innerHTML
                .replace(/INDEX/g, treatmentIndex++);
            $('#treatments-container').append(template);
        });

        // Remove Treatment
        $(document).on('click', '.remove-treatment', function() {
            $(this).closest('.treatment-item').remove();
        });

        // Add Prescription
        $('#addPrescription').click(function() {
            const template = document.getElementById('prescription-template').innerHTML
                .replace(/INDEX/g, prescriptionIndex++);
            $('#prescriptions-container').append(template);
        });

        // Remove Prescription
        $(document).on('click', '.remove-prescription', function() {
            $(this).closest('.prescription-item').remove();
        });

        // Form validation
        $('#medicalRecordForm').submit(function(e) {
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