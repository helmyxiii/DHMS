<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class DoctorAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.doctor.login');
    }

    public function showRegistrationForm()
    {
        return view('auth.doctor.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->isDoctor()) {
                $request->session()->regenerate();
                return redirect()->intended('doctor/features');
            }
            Auth::logout();
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect or you are not a doctor.'],
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'license_number' => 'required|string|unique:doctors',
            'qualifications' => 'required|string',
            'experience' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'specialties' => 'required|array',
            'specialty' => 'required|in:dentist,dermatologist,cardiologist,gp',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'doctor',
            'specialty' => $request->specialty,
        ]);

        $doctor = Doctor::create([
            'user_id' => $user->id,
            'license_number' => $request->license_number,
            'qualifications' => $request->qualifications,
            'experience' => $request->experience,
            'phone' => $request->phone,
            'address' => $request->address
        ]);

        $doctor->specialties()->attach($request->specialties);

        Auth::login($user);

        return redirect()->intended('doctor/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
} 