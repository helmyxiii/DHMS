<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout']]);
    }

    protected function attemptLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:admin,doctor,patient'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Check if user's role matches the selected role
            if ($user->role === $request->role) {
                Log::info('User authenticated successfully', [
                    'user_id' => $user->id, 
                    'email' => $user->email,
                    'role' => $user->role
                ]);
                return true;
            }
            
            // If role doesn't match, logout and return false
            Auth::logout();
            Log::warning('Role mismatch during login', [
                'email' => $request->email,
                'selected_role' => $request->role,
                'user_role' => $user->role
            ]);
            return false;
        }

        Log::warning('Failed login attempt', ['email' => $request->email]);
        return false;
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'email' => ['These credentials do not match our records or the selected role is incorrect.'],
        ]);
    }

    protected function authenticated(Request $request, $user)
    {
        // Redirect based on role
        switch ($user->role) {
            case 'admin':
                return redirect()->intended('/admin/dashboard');
            case 'doctor':
                return redirect()->intended('/doctor/features');
            case 'patient':
                return redirect()->intended('/patient/dashboard');
            default:
                return redirect()->intended($this->redirectPath());
        }
    }
}
