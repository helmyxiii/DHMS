<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:patient,doctor,admin'],
        ];

        if (isset($data['role']) && $data['role'] === 'patient') {
            $rules = array_merge($rules, [
                'date_of_birth' => ['required', 'date'],
                'gender' => ['required', 'in:male,female,other'],
                'phone' => ['required', 'string'],
                'address' => ['required', 'string'],
            ]);
        }
        if (isset($data['role']) && $data['role'] === 'doctor') {
            $rules = array_merge($rules, [
                'license_number' => ['required', 'string'],
                'qualifications' => ['required', 'string'],
                'experience' => ['required', 'string'],
                'phone' => ['required', 'string'],
                'address' => ['required', 'string'],
            ]);
        }

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        Log::info('RegisterController@create data:', $data);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        Log::info('User created:', ['id' => $user->id, 'role' => $user->role]);

        if ($data['role'] === 'patient') {
            $patient = \App\Models\Patient::create([
                'user_id' => $user->id,
                'date_of_birth' => $data['date_of_birth'] ?? now()->subYears(20),
                'gender' => $data['gender'] ?? 'other',
                'phone' => $data['phone'] ?? '',
                'address' => $data['address'] ?? '',
            ]);
            Log::info('Patient created:', $patient->toArray());
        } elseif ($data['role'] === 'doctor') {
            $doctor = \App\Models\Doctor::create([
                'user_id' => $user->id,
                'license_number' => $data['license_number'] ?? '',
                'qualifications' => $data['qualifications'] ?? '',
                'experience' => $data['experience'] ?? '',
                'phone' => $data['phone'] ?? '',
                'address' => $data['address'] ?? '',
            ]);
            Log::info('Doctor created:', $doctor->toArray());
        }

        return $user;
    }
}
