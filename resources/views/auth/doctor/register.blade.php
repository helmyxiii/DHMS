@extends('layouts.app')

@section('title', 'Doctor Registration - Zawar')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Doctor Registration</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('doctor.register') }}" class="needs-validation" novalidate>
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" placeholder="Full Name" 
                                           value="{{ old('name') }}" required autofocus>
                                    <label for="name">Full Name</label>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" placeholder="name@example.com" 
                                           value="{{ old('email') }}" required>
                                    <label for="email">Email address</label>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" placeholder="Password" required>
                                    <label for="password">Password</label>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" 
                                           placeholder="Confirm Password" required>
                                    <label for="password_confirmation">Confirm Password</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control @error('license_number') is-invalid @enderror" 
                                   id="license_number" name="license_number" placeholder="License Number" 
                                   value="{{ old('license_number') }}" required>
                            <label for="license_number">Medical License Number</label>
                            @error('license_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <select class="form-select @error('specialty') is-invalid @enderror" id="specialty" name="specialty" required>
                                <option value="">Select Specialty</option>
                                <option value="dentist" {{ old('specialty') == 'dentist' ? 'selected' : '' }}>Dentist</option>
                                <option value="dermatologist" {{ old('specialty') == 'dermatologist' ? 'selected' : '' }}>Dermatologist</option>
                                <option value="cardiologist" {{ old('specialty') == 'cardiologist' ? 'selected' : '' }}>Cardiologist</option>
                                <option value="gp" {{ old('specialty') == 'gp' ? 'selected' : '' }}>General Practitioner</option>
                            </select>
                            <label for="specialty">Primary Specialty</label>
                            @error('specialty')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <select class="form-select @error('specialties') is-invalid @enderror" 
                                    id="specialties" name="specialties[]" multiple required>
                                @if(isset($specialties))
                                    @foreach($specialties as $specialty)
                                        <option value="{{ $specialty->id }}" 
                                            {{ in_array($specialty->id, old('specialties', [])) ? 'selected' : '' }}>
                                            {{ $specialty->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="specialties">Specialties</label>
                            @error('specialties')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <textarea class="form-control @error('qualifications') is-invalid @enderror" 
                                      id="qualifications" name="qualifications" 
                                      placeholder="Qualifications" style="height: 100px" required>{{ old('qualifications') }}</textarea>
                            <label for="qualifications">Qualifications</label>
                            @error('qualifications')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <textarea class="form-control @error('experience') is-invalid @enderror" 
                                      id="experience" name="experience" 
                                      placeholder="Experience" style="height: 100px" required>{{ old('experience') }}</textarea>
                            <label for="experience">Experience</label>
                            @error('experience')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input @error('terms') is-invalid @enderror" 
                                   type="checkbox" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="{{ route('terms') }}" target="_blank">Terms and Conditions</a>
                            </label>
                            @error('terms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Register as Doctor
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">
                        Already have an account? <a href="{{ route('doctor.login') }}">Sign In</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#specialties').select2({
            theme: 'bootstrap-5',
            placeholder: 'Select specialties',
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush 