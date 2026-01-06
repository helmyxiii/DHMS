<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Specialty;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialties = [
            [
                'name' => 'Cardiology',
                'description' => 'Specializes in the diagnosis and treatment of heart disorders.'
            ],
            [
                'name' => 'Dermatology',
                'description' => 'Focuses on the diagnosis and treatment of skin disorders.'
            ],
            [
                'name' => 'Endocrinology',
                'description' => 'Specializes in the diagnosis and treatment of hormone-related disorders.'
            ],
            [
                'name' => 'Gastroenterology',
                'description' => 'Focuses on the digestive system and its disorders.'
            ],
            [
                'name' => 'Neurology',
                'description' => 'Specializes in the diagnosis and treatment of nervous system disorders.'
            ],
            [
                'name' => 'Obstetrics and Gynecology',
                'description' => 'Focuses on women\'s health, pregnancy, and childbirth.'
            ],
            [
                'name' => 'Ophthalmology',
                'description' => 'Specializes in eye and vision care.'
            ],
            [
                'name' => 'Orthopedics',
                'description' => 'Focuses on the musculoskeletal system and its disorders.'
            ],
            [
                'name' => 'Pediatrics',
                'description' => 'Specializes in the care of infants, children, and adolescents.'
            ],
            [
                'name' => 'Psychiatry',
                'description' => 'Focuses on the diagnosis and treatment of mental disorders.'
            ],
            [
                'name' => 'Pulmonology',
                'description' => 'Specializes in respiratory system disorders.'
            ],
            [
                'name' => 'Urology',
                'description' => 'Focuses on the urinary system and male reproductive organs.'
            ]
        ];

        foreach ($specialties as $specialty) {
            Specialty::updateOrCreate(
                ['slug' => Str::slug($specialty['name'])],
                [
                    'name' => $specialty['name'],
                    'description' => $specialty['description']
                ]
            );
        }
    }
} 