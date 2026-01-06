<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Doctor::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->doctor(),
            'specialty_id' => Specialty::factory(),
            'license_number' => 'MD' . fake()->unique()->numerify('######'),
            'qualifications' => fake()->randomElement(['MD', 'MD, PhD', 'MD, FACC', 'MD, FAAP']) . 
                              ', ' . fake()->randomElement(['Board Certified', 'Fellowship Trained']),
            'experience' => fake()->numberBetween(1, 30) . ' years of experience',
        ];
    }

    /**
     * Configure the model factory to create a doctor with a specific specialty.
     */
    public function withSpecialty(Specialty $specialty): static
    {
        return $this->state(fn (array $attributes) => [
            'specialty_id' => $specialty->id,
        ]);
    }

    /**
     * Configure the model factory to create a doctor with a specific user.
     */
    public function withUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Configure the model factory to create a doctor with specific qualifications.
     */
    public function withQualifications(string $qualifications): static
    {
        return $this->state(fn (array $attributes) => [
            'qualifications' => $qualifications,
        ]);
    }

    /**
     * Configure the model factory to create a doctor with specific experience.
     */
    public function withExperience(int $years): static
    {
        return $this->state(fn (array $attributes) => [
            'experience' => $years . ' years of experience',
        ]);
    }

    /**
     * Configure the model factory to create a doctor with a specific license number.
     */
    public function withLicenseNumber(string $licenseNumber): static
    {
        return $this->state(fn (array $attributes) => [
            'license_number' => $licenseNumber,
        ]);
    }

    /**
     * Configure the model factory to create a new doctor (less than 5 years experience).
     */
    public function newDoctor(): static
    {
        return $this->state(fn (array $attributes) => [
            'experience' => fake()->numberBetween(1, 5) . ' years of experience',
            'qualifications' => 'MD, ' . fake()->randomElement(['Residency Completed', 'Board Eligible']),
        ]);
    }

    /**
     * Configure the model factory to create a senior doctor (more than 15 years experience).
     */
    public function seniorDoctor(): static
    {
        return $this->state(fn (array $attributes) => [
            'experience' => fake()->numberBetween(15, 30) . ' years of experience',
            'qualifications' => fake()->randomElement(['MD, PhD', 'MD, FACC', 'MD, FAAP']) . 
                              ', ' . fake()->randomElement(['Board Certified', 'Fellowship Trained']),
        ]);
    }
} 