<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MyPatientMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !Auth::user()->isPatient()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized. Patient access required.'], 403);
            }
            return redirect()->route('patient.login')->with('error', 'Unauthorized. Patient access required.');
        }
        return $next($request);
    }
}
