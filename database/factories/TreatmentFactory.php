<?php

namespace Database\Factories;

use App\Models\MedicalRecord;
use App\Models\Treatment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Treatment>
 */
class TreatmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Treatment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-1 month', '+1 month');
        $endDate = fake()->optional(0.7)->dateTimeBetween($startDate, '+6 months');
        
        return [
            'medical_record_id' => MedicalRecord::factory(),
            'name' => fake()->randomElement([
                'Medication therapy',
                'Physical therapy',
                'Lifestyle modification',
                'Dietary changes',
                'Exercise program',
                'Counseling',
                'Surgery',
                'Rest and recovery'
            ]),
            'status' => fake()->randomElement(['ongoing', 'completed', 'cancelled']),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'description' => fake()->sentence(),
        ];
    }

    /**
     * Configure the model factory to create a treatment with a specific medical record.
     */
    public function forMedicalRecord(MedicalRecord $medicalRecord): static
    {
        return $this->state(fn (array $attributes) => [
            'medical_record_id' => $medicalRecord->id,
        ]);
    }

    /**
     * Configure the model factory to create an ongoing treatment.
     */
    public function ongoing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'ongoing',
            'start_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'end_date' => null,
        ]);
    }

    /**
     * Configure the model factory to create a completed treatment.
     */
    public function completed(): static
    {
        $startDate = fake()->dateTimeBetween('-6 months', '-1 month');
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'start_date' => $startDate,
            'end_date' => fake()->dateTimeBetween($startDate, 'now'),
        ]);
    }

    /**
     * Configure the model factory to create a cancelled treatment.
     */
    public function cancelled(): static
    {
        $startDate = fake()->dateTimeBetween('-3 months', 'now');
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'start_date' => $startDate,
            'end_date' => fake()->dateTimeBetween($startDate, 'now'),
        ]);
    }

    /**
     * Configure the model factory to create a medication therapy treatment.
     */
    public function medicationTherapy(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Medication therapy',
            'description' => 'Prescribed medication treatment plan',
        ]);
    }

    /**
     * Configure the model factory to create a physical therapy treatment.
     */
    public function physicalTherapy(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Physical therapy',
            'description' => 'Physical therapy sessions for rehabilitation',
        ]);
    }

    /**
     * Configure the model factory to create a lifestyle modification treatment.
     */
    public function lifestyleModification(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Lifestyle modification',
            'description' => 'Changes to daily habits and routines for better health',
        ]);
    }

    /**
     * Configure the model factory to create a dietary changes treatment.
     */
    public function dietaryChanges(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Dietary changes',
            'description' => 'Specific dietary modifications for health improvement',
        ]);
    }

    /**
     * Configure the model factory to create an exercise program treatment.
     */
    public function exerciseProgram(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Exercise program',
            'description' => 'Structured exercise routine for physical fitness',
        ]);
    }

    /**
     * Configure the model factory to create a counseling treatment.
     */
    public function counseling(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Counseling',
            'description' => 'Psychological counseling sessions',
        ]);
    }

    /**
     * Configure the model factory to create a surgery treatment.
     */
    public function surgery(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Surgery',
            'description' => 'Surgical procedure for treatment',
        ]);
    }

    /**
     * Configure the model factory to create a rest and recovery treatment.
     */
    public function restAndRecovery(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Rest and recovery',
            'description' => 'Rest period for recovery and healing',
        ]);
    }

    /**
     * Configure the model factory to create a treatment with a specific name.
     */
    public function withName(string $name): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $name,
        ]);
    }

    /**
     * Configure the model factory to create a treatment with a specific description.
     */
    public function withDescription(string $description): static
    {
        return $this->state(fn (array $attributes) => [
            'description' => $description,
        ]);
    }

    /**
     * Configure the model factory to create a treatment with specific dates.
     */
    public function withDates(Carbon $startDate, ?Carbon $endDate = null): static
    {
        return $this->state(fn (array $attributes) => [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $endDate ? 'completed' : 'ongoing',
        ]);
    }
} 