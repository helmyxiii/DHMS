<?php

namespace Database\Factories;

use App\Models\MedicalRecord;
use App\Models\Prescription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prescription>
 */
class PrescriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Prescription::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'medical_record_id' => MedicalRecord::factory(),
            'medicine_name' => fake()->randomElement([
                'Amoxicillin',
                'Ibuprofen',
                'Omeprazole',
                'Metformin',
                'Lisinopril',
                'Atorvastatin',
                'Albuterol',
                'Sertraline',
                'Levothyroxine',
                'Amlodipine',
                'Metoprolol',
                'Gabapentin',
                'Hydrochlorothiazide',
                'Losartan',
                'Simvastatin'
            ]),
            'dosage' => fake()->randomElement([
                '250mg',
                '500mg',
                '1000mg',
                '10mg',
                '20mg',
                '40mg',
                '50mg',
                '75mg',
                '100mg',
                '150mg',
                '200mg',
                '300mg',
                '400mg',
                '500mg',
                '1000mg'
            ]),
            'frequency' => fake()->randomElement([
                'Once daily',
                'Twice daily',
                'Three times daily',
                'Every 6 hours',
                'Every 8 hours',
                'Every 12 hours',
                'Every 24 hours',
                'As needed',
                'Before meals',
                'After meals',
                'With meals',
                'At bedtime'
            ]),
            'duration' => fake()->randomElement([
                '5 days',
                '7 days',
                '10 days',
                '14 days',
                '21 days',
                '30 days',
                '60 days',
                '90 days',
                'Ongoing',
                'Until finished',
                'As directed'
            ]),
            'instructions' => fake()->sentence(),
        ];
    }

    /**
     * Configure the model factory to create a prescription with a specific medical record.
     */
    public function forMedicalRecord(MedicalRecord $medicalRecord): static
    {
        return $this->state(fn (array $attributes) => [
            'medical_record_id' => $medicalRecord->id,
        ]);
    }

    /**
     * Configure the model factory to create an antibiotic prescription.
     */
    public function antibiotic(): static
    {
        return $this->state(fn (array $attributes) => [
            'medicine_name' => fake()->randomElement([
                'Amoxicillin',
                'Azithromycin',
                'Ciprofloxacin',
                'Doxycycline',
                'Clarithromycin'
            ]),
            'dosage' => fake()->randomElement(['250mg', '500mg', '750mg', '1000mg']),
            'frequency' => fake()->randomElement([
                'Twice daily',
                'Three times daily',
                'Every 8 hours',
                'Every 12 hours'
            ]),
            'duration' => fake()->randomElement(['5 days', '7 days', '10 days', '14 days']),
            'instructions' => 'Take with food to reduce stomach upset. Complete the full course even if symptoms improve.',
        ]);
    }

    /**
     * Configure the model factory to create a pain medication prescription.
     */
    public function painMedication(): static
    {
        return $this->state(fn (array $attributes) => [
            'medicine_name' => fake()->randomElement([
                'Ibuprofen',
                'Acetaminophen',
                'Naproxen',
                'Diclofenac',
                'Celecoxib'
            ]),
            'dosage' => fake()->randomElement(['200mg', '400mg', '500mg', '650mg', '1000mg']),
            'frequency' => fake()->randomElement([
                'Every 4 hours as needed',
                'Every 6 hours as needed',
                'Every 8 hours as needed',
                'Twice daily',
                'Three times daily'
            ]),
            'duration' => fake()->randomElement(['3 days', '5 days', '7 days', 'As needed']),
            'instructions' => 'Take with food if needed. Do not exceed recommended dosage.',
        ]);
    }

    /**
     * Configure the model factory to create a blood pressure medication prescription.
     */
    public function bloodPressureMedication(): static
    {
        return $this->state(fn (array $attributes) => [
            'medicine_name' => fake()->randomElement([
                'Lisinopril',
                'Amlodipine',
                'Metoprolol',
                'Losartan',
                'Hydrochlorothiazide'
            ]),
            'dosage' => fake()->randomElement(['5mg', '10mg', '20mg', '25mg', '40mg', '50mg']),
            'frequency' => 'Once daily',
            'duration' => 'Ongoing',
            'instructions' => 'Take in the morning. Monitor blood pressure regularly.',
        ]);
    }

    /**
     * Configure the model factory to create a diabetes medication prescription.
     */
    public function diabetesMedication(): static
    {
        return $this->state(fn (array $attributes) => [
            'medicine_name' => fake()->randomElement([
                'Metformin',
                'Glipizide',
                'Glimepiride',
                'Sitagliptin',
                'Empagliflozin'
            ]),
            'dosage' => fake()->randomElement(['500mg', '850mg', '1000mg', '2.5mg', '5mg']),
            'frequency' => fake()->randomElement([
                'Once daily',
                'Twice daily',
                'Three times daily',
                'With meals'
            ]),
            'duration' => 'Ongoing',
            'instructions' => 'Take with meals. Monitor blood sugar levels regularly.',
        ]);
    }

    /**
     * Configure the model factory to create a cholesterol medication prescription.
     */
    public function cholesterolMedication(): static
    {
        return $this->state(fn (array $attributes) => [
            'medicine_name' => fake()->randomElement([
                'Atorvastatin',
                'Simvastatin',
                'Rosuvastatin',
                'Pravastatin',
                'Lovastatin'
            ]),
            'dosage' => fake()->randomElement(['10mg', '20mg', '40mg', '80mg']),
            'frequency' => 'Once daily at bedtime',
            'duration' => 'Ongoing',
            'instructions' => 'Take at bedtime. Avoid grapefruit juice. Regular liver function tests required.',
        ]);
    }

    /**
     * Configure the model factory to create a prescription with a specific medicine.
     */
    public function withMedicine(string $medicineName): static
    {
        return $this->state(fn (array $attributes) => [
            'medicine_name' => $medicineName,
        ]);
    }

    /**
     * Configure the model factory to create a prescription with a specific dosage.
     */
    public function withDosage(string $dosage): static
    {
        return $this->state(fn (array $attributes) => [
            'dosage' => $dosage,
        ]);
    }

    /**
     * Configure the model factory to create a prescription with a specific frequency.
     */
    public function withFrequency(string $frequency): static
    {
        return $this->state(fn (array $attributes) => [
            'frequency' => $frequency,
        ]);
    }

    /**
     * Configure the model factory to create a prescription with a specific duration.
     */
    public function withDuration(string $duration): static
    {
        return $this->state(fn (array $attributes) => [
            'duration' => $duration,
        ]);
    }

    /**
     * Configure the model factory to create a prescription with specific instructions.
     */
    public function withInstructions(string $instructions): static
    {
        return $this->state(fn (array $attributes) => [
            'instructions' => $instructions,
        ]);
    }
} 