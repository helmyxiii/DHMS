<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = [
            [
                'email' => 'john.smith@example.com',
                'specialty' => 'Cardiology',
                'license_number' => 'MD123456',
                'qualifications' => 'MD, FACC',
                'experience' => '15 years of experience in cardiology',
            ],
            [
                'email' => 'sarah.johnson@example.com',
                'specialty' => 'Pediatrics',
                'license_number' => 'MD234567',
                'qualifications' => 'MD, FAAP',
                'experience' => '10 years of experience in pediatrics',
            ],
            [
                'email' => 'michael.brown@example.com',
                'specialty' => 'Neurology',
                'license_number' => 'MD345678',
                'qualifications' => 'MD, PhD',
                'experience' => '12 years of experience in neurology',
            ],
        ];

        foreach ($doctors as $doctorData) {
            $user = User::where('email', $doctorData['email'])->first();
            $specialty = Specialty::where('name', $doctorData['specialty'])->first();

            if ($user && $specialty) {
                Doctor::updateOrCreate(
                    ['license_number' => $doctorData['license_number']],
                    [
                        'user_id' => $user->id,
                        'specialty_id' => $specialty->id,
                        'qualifications' => $doctorData['qualifications'],
                        'experience' => $doctorData['experience'],
                    ]
                );
            }
        }
    }
} 