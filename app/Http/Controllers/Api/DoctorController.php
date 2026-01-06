<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Appointment;

class DoctorController extends Controller
{
    public function availableSlots(Request $request, $doctorId)
    {
        $date = $request->query('date');
        if (!$date) {
            return response()->json(['slots' => []]);
        }

        $schedules = Schedule::where('doctor_id', $doctorId)
            ->where('date', $date)
            ->where('is_available', true)
            ->get();

        $slots = [];
        foreach ($schedules as $schedule) {
            $start = strtotime($schedule->start_time);
            $end = strtotime($schedule->end_time);
            while ($start < $end) {
                $slotStart = date('H:i', $start);
                $slotEnd = date('H:i', $start + 30*60); // 30 min slots
                if (strtotime($slotEnd) > $end) break;

                $exists = Appointment::where('doctor_id', $doctorId)
                    ->where('appointment_date', $date)
                    ->where('time_slot', "$slotStart-$slotEnd")
                    ->where('status', '!=', 'cancelled')
                    ->exists();

                if (!$exists) {
                    $slots[] = "$slotStart-$slotEnd";
                }
                $start += 30*60;
            }
        }

        return response()->json(['slots' => $slots]);
    }
} 