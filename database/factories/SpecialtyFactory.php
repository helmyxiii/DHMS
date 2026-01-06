<?php

namespace Database\Factories;

use App\Models\Specialty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Specialty>
 */
class SpecialtyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Specialty::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $specialties = [
            'Cardiology',
            'Dermatology',
            'Endocrinology',
            'Gastroenterology',
            'General Medicine',
            'Neurology',
            'Obstetrics and Gynecology',
            'Ophthalmology',
            'Orthopedics',
            'Pediatrics',
            'Psychiatry',
            'Pulmonology',
            'Rheumatology',
            'Urology',
        ];

        $specialty = fake()->unique()->randomElement($specialties);

        return [
            'name' => $specialty,
            'description' => "Specialized medical care in the field of {$specialty}",
        ];
    }
} 