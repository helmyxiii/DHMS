<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $schedules = [];

        if ($user->isAdmin()) {
            $schedules = Schedule::with(['doctor.user'])
                ->where('date', '>=', now())
                ->orderBy('date')
                ->orderBy('start_time')
                ->paginate(10);
        } elseif ($user->isDoctor()) {
            $schedules = $user->doctor->schedules()
                ->where('date', '>=', now())
                ->orderBy('date')
                ->orderBy('start_time')
                ->paginate(10);
        }

        return view('schedules.index', compact('schedules'));
    }

    public function create()
    {
        if (!Auth::user()->isDoctor()) {
            abort(403);
        }

        return view('schedules.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isDoctor()) {
            abort(403);
        }

        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_patients' => 'required|integer|min:1',
            'is_available' => 'boolean'
        ]);

        // Check for overlapping schedules
        $overlapping = Schedule::where('doctor_id', Auth::user()->doctor->id)
            ->where('date', $request->date)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })
            ->exists();

        if ($overlapping) {
            return back()->withErrors(['schedule' => 'This time slot overlaps with an existing schedule']);
        }

        Auth::user()->doctor->schedules()->create($request->all());
        return redirect()->route('schedules.index')
            ->with('success', 'Schedule created successfully');
    }

    public function edit(Schedule $schedule)
    {
        if (!Auth::user()->isDoctor() || $schedule->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        // Check if schedule has any non-cancelled appointments
        $hasAppointments = Appointment::where('schedule_id', $schedule->id)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($hasAppointments) {
            return back()->withErrors(['schedule' => 'Cannot edit schedule with active appointments']);
        }

        return view('schedules.edit', compact('schedule'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        if (!Auth::user()->isDoctor() || $schedule->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        // Check if schedule has any non-cancelled appointments
        $hasAppointments = Appointment::where('schedule_id', $schedule->id)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($hasAppointments) {
            return back()->withErrors(['schedule' => 'Cannot update schedule with active appointments']);
        }

        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_patients' => 'required|integer|min:1',
            'is_available' => 'boolean'
        ]);

        // Check for overlapping schedules
        $overlapping = Schedule::where('doctor_id', Auth::user()->doctor->id)
            ->where('date', $request->date)
            ->where('id', '!=', $schedule->id)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })
            ->exists();

        if ($overlapping) {
            return back()->withErrors(['schedule' => 'This time slot overlaps with an existing schedule']);
        }

        $schedule->update($request->all());
        return redirect()->route('schedules.index')
            ->with('success', 'Schedule updated successfully');
    }

    public function destroy(Schedule $schedule)
    {
        if (!Auth::user()->isDoctor() || $schedule->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        // Check if schedule has any non-cancelled appointments
        $hasAppointments = Appointment::where('schedule_id', $schedule->id)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($hasAppointments) {
            return back()->withErrors(['schedule' => 'Cannot delete schedule with active appointments']);
        }

        $schedule->delete();
        return redirect()->route('schedules.index')
            ->with('success', 'Schedule deleted successfully');
    }

    public function toggleAvailability(Schedule $schedule)
    {
        if (!Auth::user()->isDoctor() || $schedule->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        // Check if schedule has any non-cancelled appointments
        $hasAppointments = Appointment::where('schedule_id', $schedule->id)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($hasAppointments && $schedule->is_available) {
            return back()->withErrors(['schedule' => 'Cannot make unavailable schedule with active appointments']);
        }

        $schedule->update(['is_available' => !$schedule->is_available]);
        return back()->with('success', 
            $schedule->is_available ? 'Schedule made available' : 'Schedule made unavailable'
        );
    }
} 