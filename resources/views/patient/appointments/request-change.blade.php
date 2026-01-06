@extends('layouts.app')

@section('title', 'Request Change - Appointment')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Request Change for Appointment</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('patient.appointments.request-change') }}">
                        @csrf
                        <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                        <div class="mb-3">
                            <label for="change_type" class="form-label">Change Type</label>
                            <select name="change_type" id="change_type" class="form-select" required>
                                <option value="change">Change</option>
                                <option value="cancel">Cancel</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea name="message" id="message" class="form-control" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary ms-2">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 