<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);
        $user->update($request->only('name', 'email'));
        if ($user->role === 'doctor' && $request->filled('specialty')) {
            $user->specialty = $request->specialty;
            $user->save();
        }
        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function destroy()
    {
        $user = Auth::user();
        $user->delete();
        Auth::logout();
        return redirect('/')->with('success', 'Account deleted successfully.');
    }
} 