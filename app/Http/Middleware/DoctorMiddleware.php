<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->isDoctor()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized. Doctor access required.'], 403);
            }
            return redirect()->route('doctor.login')->with('error', 'Unauthorized. Doctor access required.');
        }

        return $next($request);
    }
} 