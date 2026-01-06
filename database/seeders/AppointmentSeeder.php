<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = Doctor::all();
        $patients = Patient::all();
        $appointmentTypes = ['consultation', 'follow-up', 'regular checkup', 'emergency'];
        $statuses = ['scheduled', 'completed', 'cancelled'];

        // Create appointments for the next 30 days
        for ($i = 0; $i < 20; $i++) {
            $doctor = $doctors->random();
            $patient = $patients->random();
            $type = $appointmentTypes[array_rand($appointmentTypes)];
            $status = $statuses[array_rand($statuses)];
            
            // Generate a random date and time between now and 30 days from now
            $date = Carbon::now()->addDays(rand(0, 30));
            $hour = rand(9, 16); // Between 9 AM and 4 PM
            $minute = rand(0, 3) * 15; // 0, 15, 30, or 45 minutes
            $appointmentDate = $date->copy()->setHour($hour)->setMinute($minute);

            // If the appointment is in the past, mark it as completed or cancelled
            if ($appointmentDate->isPast()) {
                $status = rand(0, 1) ? 'completed' : 'cancelled';
            }

            // Check if the patient exists
            if ($patient) {
                $user = \App\Models\User::find($patient->id);
                if ($user) {
                    Appointment::updateOrCreate(
                        [
                            'doctor_id' => $doctor->id,
                            'patient_id' => $patient->id,
                            'appointment_date' => $appointmentDate,
                        ],
                        [
                            'type' => $type,
                            'status' => $status,
                            'reason' => "Appointment for {$type}",
                            'notes' => rand(0, 1) ? "Additional notes for the appointment" : null,
                        ]
                    );
                } else {
                    // Log or handle the case where the user does not exist
                    \Log::warning("User with ID {$patient->id} does not exist. Skipping appointment creation.");
                }
            } else {
                // Log or handle the case where the patient does not exist
                \Log::warning("Patient with ID {$patient->id} does not exist. Skipping appointment creation.");
            }
        }

        // Create some emergency appointments
        for ($i = 0; $i < 3; $i++) {
            $doctor = $doctors->random();
            $patient = $patients->random();
            
            // Generate a random date and time within the last 7 days
            $date = Carbon::now()->subDays(rand(0, 7));
            $hour = rand(0, 23);
            $minute = rand(0, 3) * 15;
            $appointmentDate = $date->copy()->setHour($hour)->setMinute($minute);

            // Check if the patient exists
            if ($patient) {
                $user = \App\Models\User::find($patient->id);
                if ($user) {
                    Appointment::updateOrCreate(
                        [
                            'doctor_id' => $doctor->id,
                            'patient_id' => $patient->id,
                            'appointment_date' => $appointmentDate,
                        ],
                        [
                            'type' => 'emergency',
                            'status' => 'completed',
                            'reason' => 'Emergency medical attention required',
                            'notes' => 'Patient received immediate care',
                        ]
                    );
                } else {
                    // Log or handle the case where the user does not exist
                    \Log::warning("User with ID {$patient->id} does not exist. Skipping appointment creation.");
                }
            } else {
                // Log or handle the case where the patient does not exist
                \Log::warning("Patient with ID {$patient->id} does not exist. Skipping appointment creation.");
            }
        }
    }
} 