<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Zawar Hospital Management System')</title>
    
    {{-- Meta Tags --}}
    @yield('meta')
    
    {{-- Styles --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/patient.css') }}">
    @yield('styles')
    @stack('styles')
</head>
<body class="@stack('body-class')">
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/zawar.jpeg') }}" alt="Zawar" height="40"> Zawar
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
    @auth
        @if(Auth::user()->hasRole('doctor'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}" href="{{ route('doctor.dashboard') }}">Home</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}" alt="Avatar" class="rounded-circle me-2" width="32" height="32">
                    <span class="fw-semibold">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-0" aria-labelledby="navbarDropdown" style="min-width: 260px;">
                    <li class="bg-light px-3 py-3 rounded-top border-bottom">
                        <div class="d-flex align-items-center">
                            <img src="{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}" alt="Avatar" class="rounded-circle me-2" width="48" height="48">
                            <div>
                                <div class="fw-bold">{{ Auth::user()->name }}</div>
                                <div class="text-muted small">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                    </li>
                    <li class="px-3 pt-2 pb-1 text-muted small">Account</li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user-edit me-2 text-primary"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button class="dropdown-item d-flex align-items-center text-danger" type="submit">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        @elseif(Auth::user()->hasRole('admin'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}" alt="Avatar" class="rounded-circle me-2" width="32" height="32">
                    <span class="fw-semibold">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-0" aria-labelledby="navbarDropdown" style="min-width: 260px;">
                    <li class="bg-light px-3 py-3 rounded-top border-bottom">
                        <div class="d-flex align-items-center">
                            <img src="{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}" alt="Avatar" class="rounded-circle me-2" width="48" height="48">
                            <div>
                                <div class="fw-bold">{{ Auth::user()->name }}</div>
                                <div class="text-muted small">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                    </li>
                    <li class="px-3 pt-2 pb-1 text-muted small">Account</li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user-edit me-2 text-primary"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button class="dropdown-item d-flex align-items-center text-danger" type="submit">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        @else
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ url('/') }}">Home</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ url('/about') }}">About</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}" href="{{ route('appointments.index') }}">Appointments</a></li>
            @if(Auth::user()->hasRole('patient'))
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('patient.medical-records.*') ? 'active' : '' }}" href="{{ route('patient.medical-records.index') }}">Medical Records</a></li>
            @endif
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('health-tips.*') ? 'active' : '' }}" href="{{ route('health-tips.index') }}">Health Tips</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a></li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}" alt="Avatar" class="rounded-circle me-2" width="32" height="32">
                    <span class="fw-semibold">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-0" aria-labelledby="navbarDropdown" style="min-width: 260px;">
                    <li class="bg-light px-3 py-3 rounded-top border-bottom">
                        <div class="d-flex align-items-center">
                            <img src="{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}" alt="Avatar" class="rounded-circle me-2" width="48" height="48">
                            <div>
                                <div class="fw-bold">{{ Auth::user()->name }}</div>
                                <div class="text-muted small">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                    </li>
                    <li class="px-3 pt-2 pb-1 text-muted small">Account</li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user-edit me-2 text-primary"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button class="dropdown-item d-flex align-items-center text-danger" type="submit">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        @endif
    @else
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ url('/') }}">Home</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ url('/about') }}">About</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a></li>
        <li class="nav-item">
            <a class="btn btn-outline-light btn-sm ms-2" href="{{ route('login') }}">Login</a>
        </li>
        <li class="nav-item">
            <a class="btn btn-light btn-sm ms-2" href="{{ route('register') }}">Sign Up</a>
        </li>
    @endauth
</ul>

            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container text-center">
            <span class="text-muted">&copy; {{ date('Y') }} Zawar Hospital Management System. All rights reserved.</span>
        </div>
    </footer>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
    @stack('scripts')

    <style>
    .navbar-nav .btn {
        padding: 0.4rem 0.9rem;
        font-size: 0.875rem;
        margin-top: 4px;
    }
    .navbar .dropdown-menu {
        left: auto !important;
        right: 0;
        top: 100%;
        transform: none !important;
    }
    .dropdown-menu .dropdown-item:hover, 
    .dropdown-menu .dropdown-item:focus {
        background-color: #f0f4f8;
        color: #0d6efd;
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }
    </style>
</body>
</html> 