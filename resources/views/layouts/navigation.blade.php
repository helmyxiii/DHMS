@php
    $currentRoute = request()->route()->getName();
@endphp

<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link {{ $currentRoute === 'admin.dashboard' ? 'active' : '' }}" 
           href="{{ route('admin.dashboard') }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
    </li>

    @if($currentRoute === 'admin.doctors')
        <li class="nav-item">
            <a class="nav-link {{ $currentRoute === 'admin.doctors' ? 'active' : '' }}" 
               href="{{ route('admin.doctors.index') }}">
                <i class="bi bi-person-badge"></i> Doctors
            </a>
        </li>
    @endif

    @if($currentRoute === 'admin.patients')
        <li class="nav-item">
            <a class="nav-link {{ $currentRoute === 'admin.patients' ? 'active' : '' }}" 
               href="{{ route('admin.patients.index') }}">
                <i class="bi bi-people"></i> Patients
            </a>
        </li>
    @endif

    @if($currentRoute === 'admin.specialties')
        <li class="nav-item">
            <a class="nav-link {{ $currentRoute === 'admin.specialties' ? 'active' : '' }}" 
               href="{{ route('admin.specialties.index') }}">
                <i class="bi bi-list-check"></i> Specialties
            </a>
        </li>
    @endif

    @if($currentRoute === 'admin.appointments')
        <li class="nav-item">
            <a class="nav-link {{ $currentRoute === 'admin.appointments' ? 'active' : '' }}" 
               href="{{ route('admin.appointments.index') }}">
                <i class="bi bi-calendar-check"></i> Appointments
            </a>
        </li>
    @endif

    @if($currentRoute === 'admin.medical-records')
        <li class="nav-item">
            <a class="nav-link {{ $currentRoute === 'admin.medical-records' ? 'active' : '' }}" 
               href="{{ route('admin.medical-records.index') }}">
                <i class="bi bi-file-medical"></i> Medical Records
            </a>
        </li>
    @endif

    @if($currentRoute === 'admin.reports')
        <li class="nav-item">
            <a class="nav-link {{ $currentRoute === 'admin.reports' ? 'active' : '' }}" 
               href="{{ route('admin.reports.index') }}">
                <i class="bi bi-file-earmark-text"></i> Reports
            </a>
        </li>
    @endif

    @if($currentRoute === 'doctor.schedules')
        <li class="nav-item">
            <a class="nav-link {{ $currentRoute === 'doctor.schedules' ? 'active' : '' }}" 
               href="{{ route('doctor.schedules.index') }}">
                <i class="bi bi-calendar-week"></i> Schedule
            </a>
        </li>
    @endif

    @if($currentRoute === 'doctor.appointments')
        <li class="nav-item">
            <a class="nav-link {{ $currentRoute === 'doctor.appointments' ? 'active' : '' }}" 
               href="{{ route('doctor.appointments.index') }}">
                <i class="bi bi-calendar-check"></i> Appointments
            </a>
        </li>
    @endif

    @if($currentRoute === 'doctor.health-tips')
        <li class="nav-item">
            <a class="nav-link {{ $currentRoute === 'doctor.health-tips' ? 'active' : '' }}" 
               href="{{ route('doctor.health-tips.index') }}">
                <i class="bi bi-lightbulb"></i> Health Tips
            </a>
        </li>
    @endif

    @if($currentRoute === 'doctor.reports')
        <li class="nav-item">
            <a class="nav-link {{ $currentRoute === 'doctor.reports' ? 'active' : '' }}" 
               href="{{ route('doctor.reports.index') }}">
                <i class="bi bi-file-earmark-text"></i> Reports
            </a>
        </li>
    @endif

    @if($currentRoute === 'patient.appointments')
        <li class="nav-item">
            <a class="nav-link {{ $currentRoute === 'patient.appointments' ? 'active' : '' }}" 
               href="{{ route('patient.appointments.index') }}">
                <i class="bi bi-calendar-check"></i> Appointments
            </a>
        </li>
    @endif

    @if($currentRoute === 'patient.medical-records')
        <li class="nav-item">
            <a class="nav-link {{ $currentRoute === 'patient.medical-records' ? 'active' : '' }}" 
               href="{{ route('patient.medical-records.index') }}">
                <i class="bi bi-file-medical"></i> Medical Records
            </a>
        </li>
    @endif

    @if($currentRoute === 'patient.reports')
        <li class="nav-item">
            <a class="nav-link {{ $currentRoute === 'patient.reports' ? 'active' : '' }}" 
               href="{{ route('patient.reports.index') }}">
                <i class="bi bi-file-earmark-text"></i> Reports
            </a>
        </li>
    @endif

    @if($currentRoute === 'health-tips')
        <li class="nav-item">
            <a class="nav-link {{ $currentRoute === 'health-tips' ? 'active' : '' }}" 
               href="{{ route('health-tips.index') }}">
                <i class="bi bi-lightbulb"></i> Health Tips
            </a>
        </li>
    @endif

    <li class="nav-item">
        <a class="nav-link {{ $currentRoute === 'admin.profile' ? 'active' : '' }}" 
           href="{{ route('admin.profile') }}">
            <i class="bi bi-person-circle"></i> Profile
        </a>
    </li>

    @if(auth()->user()->isAdmin())
        <li class="nav-item">
            <a class="nav-link {{ $currentRoute === 'roles.index' ? 'active' : '' }}" 
               href="{{ route('roles.index') }}">
                <i class="bi bi-person-badge"></i> Role Management
            </a>
        </li>
    @endif
</ul> 