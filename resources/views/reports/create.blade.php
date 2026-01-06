@extends('layouts.app')

@section('title', 'Generate New Report')

@section('content')
<div class="container py-5">
    <header class="text-center text-white mb-5">
        <h1 class="fw-bold">Generate New Report</h1>
        <p class="lead">Fill out the form below to generate a new report.</p>
    </header>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-custom animate">
                <div class="card-body">
                    <form method="POST" action="{{ route('doctor.reports.store') }}">
                        @csrf
                        <!-- Report Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label text-white">{{ __('Report Title') }}</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required autofocus>
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <!-- Report Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label text-white">{{ __('Description (Optional)') }}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <!-- Report Type -->
                        <div class="mb-3">
                            <label for="type" class="form-label text-white">{{ __('Report Type') }}</label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="" disabled selected>Select a report type</option>
                                @if(isset($reportTypes) && is_array($reportTypes) && count($reportTypes) > 0)
                                    @foreach($reportTypes as $slug => $name)
                                        <option value="{{ $slug }}" {{ old('type') == $slug ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                @else
                                    <option value="" disabled>No report types available. Check controller.</option>
                                @endif
                            </select>
                            @error('type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <!-- Date Range -->
                        <div class="mb-3">
                            <label class="form-label text-white">{{ __('Date Range') }}</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="date_range_start" class="form-label text-white">{{ __('Start Date') }}</label>
                                    <input type="date" class="form-control @error('date_range.start') is-invalid @enderror" id="date_range_start" name="date_range[start]" value="{{ old('date_range.start') }}" required>
                                    @error('date_range.start')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="date_range_end" class="form-label text-white">{{ __('End Date') }}</label>
                                    <input type="date" class="form-control @error('date_range.end') is-invalid @enderror" id="date_range_end" name="date_range[end]" value="{{ old('date_range.end') }}" required>
                                    @error('date_range.end')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                             @error('date_range')
                                <div class="text-danger mt-1">
                                    <small><strong>{{ $message }}</strong></small>
                                </div>
                            @enderror
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Generate Report') }}
                            </button>
                        </div>
                    </form>
                </div>
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
h1.fw-bold, .lead, .text-white, .form-label.text-white {
    color: #fff !important;
}
</style>
@endpush

@push('body-class')
bg-dashboard
@endpush 