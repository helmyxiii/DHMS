<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->hasRole('patient')) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized. Patient access required.'], 403);
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
} 