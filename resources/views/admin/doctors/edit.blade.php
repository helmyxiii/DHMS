@extends('layouts.app')

@section('title', 'Edit Doctor - Zawar')

@section('content')
<div class="container py-5">
    <h1 class="fw-bold text-white mb-4">Edit Doctor</h1>
    <div class="card card-custom animate mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.doctors.update', $doctor) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" placeholder="Name" required>
                    </div>
                    <div class="col-md-4 mb-2">
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" placeholder="Email" required>
                    </div>
                    <div class="col-md-4 mb-2">
                        <select name="specialties[]" class="form-control" multiple required>
                            @foreach($specialties as $specialty)
                                <option value="{{ $specialty->id }}" {{ in_array($specialty->id, $doctorSpecialties) ? 'selected' : '' }}>{{ $specialty->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Update Doctor</button>
                <a href="{{ route('admin.doctors') }}" class="btn btn-secondary mt-2">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection 