<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = [
            [
                'email' => 'alice.wilson@example.com',
                'date_of_birth' => '1985-06-15',
                'gender' => 'female',
                'phone' => '555-0101',
                'address' => '123 Main St, City, State 12345',
                'medical_history' => 'No significant medical history',
            ],
            [
                'email' => 'bob.thompson@example.com',
                'date_of_birth' => '1978-03-22',
                'gender' => 'male',
                'phone' => '555-0102',
                'address' => '456 Oak Ave, City, State 12345',
                'medical_history' => 'Hypertension, Type 2 Diabetes',
            ],
            [
                'email' => 'carol.davis@example.com',
                'date_of_birth' => '1992-11-08',
                'gender' => 'female',
                'phone' => '555-0103',
                'address' => '789 Pine Rd, City, State 12345',
                'medical_history' => 'Asthma, Seasonal allergies',
            ],
            [
                'email' => 'david.miller@example.com',
                'date_of_birth' => '1980-09-30',
                'gender' => 'male',
                'phone' => '555-0104',
                'address' => '321 Elm St, City, State 12345',
                'medical_history' => 'Arthritis, Previous knee surgery',
            ],
            [
                'email' => 'emma.taylor@example.com',
                'date_of_birth' => '1995-04-17',
                'gender' => 'female',
                'phone' => '555-0105',
                'address' => '654 Maple Dr, City, State 12345',
                'medical_history' => 'Migraine, Anxiety',
            ],
        ];

        foreach ($patients as $patientData) {
            $user = User::where('email', $patientData['email'])->first();

            if ($user) {
                Patient::create([
                    'user_id' => $user->id,
                    'date_of_birth' => $patientData['date_of_birth'],
                    'gender' => $patientData['gender'],
                    'phone' => $patientData['phone'],
                    'address' => $patientData['address'],
                    'medical_history' => $patientData['medical_history'],
                ]);
            }
        }
    }
} 