<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpecialtyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $specialties = Specialty::withCount('doctors')
            ->when(request('search'), function($query) {
                $query->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('description', 'like', '%' . request('search') . '%');
            })
            ->paginate(10);

        return view('specialties.index', compact('specialties'));
    }

    public function show(Specialty $specialty)
    {
        $specialty->load(['doctors.user' => function($query) {
            $query->select('id', 'name', 'email');
        }]);

        $doctors = $specialty->doctors()
            ->with(['user', 'schedules' => function($query) {
                $query->where('date', '>=', now())
                    ->where('is_available', true)
                    ->orderBy('date')
                    ->orderBy('start_time');
            }])
            ->paginate(10);

        return view('specialties.show', compact('specialty', 'doctors'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('specialties.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:specialties',
            'description' => 'required|string'
        ]);

        Specialty::create($request->all());
        return redirect()->route('specialties.index')
            ->with('success', 'Specialty created successfully');
    }

    public function edit(Specialty $specialty)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('specialties.edit', compact('specialty'));
    }

    public function update(Request $request, Specialty $specialty)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:specialties,name,' . $specialty->id,
            'description' => 'required|string'
        ]);

        $specialty->update($request->all());
        return redirect()->route('specialties.show', $specialty)
            ->with('success', 'Specialty updated successfully');
    }

    public function destroy(Specialty $specialty)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        // Check if specialty has any doctors
        if ($specialty->doctors()->exists()) {
            return back()->withErrors(['specialty' => 'Cannot delete specialty with associated doctors']);
        }

        $specialty->delete();
        return redirect()->route('specialties.index')
            ->with('success', 'Specialty deleted successfully');
    }

    public function doctors(Specialty $specialty)
    {
        $doctors = $specialty->doctors()
            ->with(['user', 'schedules' => function($query) {
                $query->where('date', '>=', now())
                    ->where('is_available', true)
                    ->orderBy('date')
                    ->orderBy('start_time');
            }])
            ->when(request('search'), function($query) {
                $query->whereHas('user', function($q) {
                    $q->where('name', 'like', '%' . request('search') . '%');
                });
            })
            ->paginate(10);

        return view('specialties.doctors', compact('specialty', 'doctors'));
    }

    public function addDoctor(Request $request, Specialty $specialty)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'doctor_id' => 'required|exists:doctors,id'
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);
        
        if ($doctor->specialties()->where('specialty_id', $specialty->id)->exists()) {
            return back()->withErrors(['doctor' => 'Doctor already has this specialty']);
        }

        $doctor->specialties()->attach($specialty->id);
        return back()->with('success', 'Doctor added to specialty successfully');
    }

    public function removeDoctor(Specialty $specialty, Doctor $doctor)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        if (!$doctor->specialties()->where('specialty_id', $specialty->id)->exists()) {
            return back()->withErrors(['doctor' => 'Doctor does not have this specialty']);
        }

        $doctor->specialties()->detach($specialty->id);
        return back()->with('success', 'Doctor removed from specialty successfully');
    }
} 