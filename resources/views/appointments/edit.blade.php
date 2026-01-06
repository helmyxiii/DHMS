@extends('layouts.app')

@section('title', 'Edit Appointment')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Appointment</h5>
                </div>
                <div class="card-body">
                    <form>
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Doctor</label>
                            <input type="text" class="form-control" value="{{ $appointment->doctor->user->name }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Patient</label>
                            <input type="text" class="form-control" value="{{ $appointment->patient->user->name }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="appointment_type" class="form-label">Appointment Type</label>
                            <select name="appointment_type" id="appointment_type" 
                                    class="form-select @error('appointment_type') is-invalid @enderror" required>
                                <option value="consultation" {{ old('appointment_type', $appointment->appointment_type) == 'consultation' ? 'selected' : '' }}>
                                    Consultation
                                </option>
                                <option value="follow_up" {{ old('appointment_type', $appointment->appointment_type) == 'follow_up' ? 'selected' : '' }}>
                                    Follow-up
                                </option>
                                <option value="checkup" {{ old('appointment_type', $appointment->appointment_type) == 'checkup' ? 'selected' : '' }}>
                                    Regular Checkup
                                </option>
                                <option value="emergency" {{ old('appointment_type', $appointment->appointment_type) == 'emergency' ? 'selected' : '' }}>
                                    Emergency
                                </option>
                            </select>
                            @error('appointment_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="appointment_date" class="form-label">Date & Time</label>
                            <input type="datetime-local" class="form-control @error('appointment_date') is-invalid @enderror" 
                                   id="appointment_date" name="appointment_date" 
                                   value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d\TH:i')) }}" required>
                            @error('appointment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason for Visit</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" 
                                      id="reason" name="reason" rows="3" required>{{ old('reason', $appointment->reason) }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="2">{{ old('notes', $appointment->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if(auth()->user()->isDoctor() || auth()->user()->isAdmin())
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" 
                                        class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="scheduled" {{ old('status', $appointment->status) == 'scheduled' ? 'selected' : '' }}>
                                        Scheduled
                                    </option>
                                    <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>
                                        Completed
                                    </option>
                                    <option value="cancelled" {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="doctor_notes" class="form-label">Doctor's Notes</label>
                                <textarea class="form-control @error('doctor_notes') is-invalid @enderror" 
                                          id="doctor_notes" name="doctor_notes" rows="3">{{ old('doctor_notes', $appointment->doctor_notes) }}</textarea>
                                @error('doctor_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('appointments.index') }}" 
                               class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Appointment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Set minimum date to today for appointment date
        const today = new Date().toISOString().split('T')[0];
        $('#appointment_date').attr('min', today + 'T00:00');

        // Handle status change
        $('#status').on('change', function() {
            const status = $(this).val();
            if (status === 'completed') {
                // You can add additional validation or UI updates here
                // For example, requiring doctor's notes when marking as completed
            }
        });
    });
</script>
@endpush 