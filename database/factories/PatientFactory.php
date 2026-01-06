<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(['male', 'female']);
        $dateOfBirth = fake()->dateTimeBetween('-80 years', '-18 years');
        $age = $dateOfBirth->diff(new \DateTime())->y;

        $medicalConditions = [
            'No significant medical history',
            'Hypertension',
            'Type 2 Diabetes',
            'Asthma',
            'Arthritis',
            'Migraine',
            'Anxiety',
            'Seasonal allergies',
            'High cholesterol',
            'Thyroid disorder',
        ];

        return [
            'user_id' => User::factory()->patient(),
            'date_of_birth' => $dateOfBirth,
            'gender' => $gender,
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'medical_history' => fake()->randomElements($medicalConditions, fake()->numberBetween(0, 3)),
        ];
    }

    /**
     * Configure the model factory to create a patient with a specific user.
     */
    public function withUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Configure the model factory to create a patient with no medical history.
     */
    public function healthy(): static
    {
        return $this->state(fn (array $attributes) => [
            'medical_history' => 'No significant medical history',
        ]);
    }

    /**
     * Configure the model factory to create a patient with chronic conditions.
     */
    public function withChronicConditions(): static
    {
        return $this->state(fn (array $attributes) => [
            'medical_history' => fake()->randomElements(
                ['Hypertension', 'Type 2 Diabetes', 'Asthma', 'Arthritis', 'High cholesterol'],
                fake()->numberBetween(1, 3)
            ),
        ]);
    }
} 