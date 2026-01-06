<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '1234567890',
                'address' => '123 Admin St'
            ]
        );

        // Create doctor user
        User::updateOrCreate(
            ['email' => 'doctor@example.com'],
            [
                'name' => 'Doctor User',
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'phone' => '2345678901',
                'address' => '456 Doctor Ave',
                'license_number' => 'DOC123',
                'qualifications' => 'MD',
                'experience' => '10 years'
            ]
        );

        // Create patient user
        User::updateOrCreate(
            ['email' => 'patient@example.com'],
            [
                'name' => 'Patient User',
                'password' => Hash::make('password'),
                'role' => 'patient',
                'phone' => '3456789012',
                'address' => '789 Patient Blvd',
                'date_of_birth' => '1990-01-01',
                'gender' => 'male'
            ]
        );

        // Create doctor users
        $doctors = [
            [
                'name' => 'Dr. John Smith',
                'email' => 'john.smith@example.com',
                'role' => 'doctor',
            ],
            [
                'name' => 'Dr. Sarah Johnson',
                'email' => 'sarah.johnson@example.com',
                'role' => 'doctor',
            ],
            [
                'name' => 'Dr. Michael Brown',
                'email' => 'michael.brown@example.com',
                'role' => 'doctor',
            ],
        ];

        foreach ($doctors as $doctor) {
            User::updateOrCreate(
                ['email' => $doctor['email']],
                [
                    'name' => $doctor['name'],
                    'password' => Hash::make('password'),
                    'role' => $doctor['role'],
                    'email_verified_at' => now(),
                ]
            );
        }

        // Create patient users
        $patients = [
            [
                'name' => 'Alice Wilson',
                'email' => 'alice.wilson@example.com',
                'role' => 'patient',
            ],
            [
                'name' => 'Bob Thompson',
                'email' => 'bob.thompson@example.com',
                'role' => 'patient',
            ],
            [
                'name' => 'Carol Davis',
                'email' => 'carol.davis@example.com',
                'role' => 'patient',
            ],
            [
                'name' => 'David Miller',
                'email' => 'david.miller@example.com',
                'role' => 'patient',
            ],
            [
                'name' => 'Emma Taylor',
                'email' => 'emma.taylor@example.com',
                'role' => 'patient',
            ],
        ];

        foreach ($patients as $patient) {
            User::updateOrCreate(
                ['email' => $patient['email']],
                [
                    'name' => $patient['name'],
                    'password' => Hash::make('password'),
                    'role' => $patient['role'],
                    'email_verified_at' => now(),
                ]
            );
        }
    }
} 