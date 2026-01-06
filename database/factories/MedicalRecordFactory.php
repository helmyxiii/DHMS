<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicalRecord>
 */
class MedicalRecordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MedicalRecord::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'doctor_id' => Doctor::factory(),
            'patient_id' => Patient::factory(),
            'appointment_id' => Appointment::factory(),
            'symptoms' => $this->generateSymptoms(),
            'diagnosis' => $this->generateDiagnosis(),
            'treatments' => $this->generateTreatments(),
            'prescriptions' => $this->generatePrescriptions(),
            'notes' => fake()->optional(0.7)->paragraph(),
        ];
    }

    /**
     * Configure the model factory to create a record with a specific doctor.
     */
    public function withDoctor(Doctor $doctor): static
    {
        return $this->state(fn (array $attributes) => [
            'doctor_id' => $doctor->id,
        ]);
    }

    /**
     * Configure the model factory to create a record with a specific patient.
     */
    public function withPatient(Patient $patient): static
    {
        return $this->state(fn (array $attributes) => [
            'patient_id' => $patient->id,
        ]);
    }

    /**
     * Configure the model factory to create a record with a specific appointment.
     */
    public function withAppointment(Appointment $appointment): static
    {
        return $this->state(fn (array $attributes) => [
            'appointment_id' => $appointment->id,
            'doctor_id' => $appointment->doctor_id,
            'patient_id' => $appointment->patient_id,
        ]);
    }

    /**
     * Configure the model factory to create a record with specific symptoms.
     */
    public function withSymptoms(array $symptoms): static
    {
        return $this->state(fn (array $attributes) => [
            'symptoms' => $symptoms,
        ]);
    }

    /**
     * Configure the model factory to create a record with specific diagnosis.
     */
    public function withDiagnosis(string $diagnosis): static
    {
        return $this->state(fn (array $attributes) => [
            'diagnosis' => $diagnosis,
        ]);
    }

    /**
     * Configure the model factory to create a record with specific notes.
     */
    public function withNotes(string $notes): static
    {
        return $this->state(fn (array $attributes) => [
            'notes' => $notes,
        ]);
    }

    /**
     * Configure the model factory to create a record for a common cold.
     */
    public function commonCold(): static
    {
        return $this->state(fn (array $attributes) => [
            'symptoms' => ['Runny nose', 'Sore throat', 'Cough', 'Mild fever'],
            'diagnosis' => 'Common cold (Viral upper respiratory infection)',
            'treatments' => [
                [
                    'name' => 'Rest and hydration',
                    'status' => 'ongoing',
                    'start_date' => now(),
                    'end_date' => now()->addDays(7),
                    'description' => 'Get plenty of rest and drink fluids',
                ],
            ],
            'prescriptions' => [
                [
                    'medicine_name' => 'Acetaminophen',
                    'dosage' => '500mg',
                    'frequency' => 'Every 6 hours as needed',
                    'duration' => '5 days',
                    'instructions' => 'Take with food if needed',
                ],
            ],
        ]);
    }

    /**
     * Configure the model factory to create a record for hypertension.
     */
    public function hypertension(): static
    {
        return $this->state(fn (array $attributes) => [
            'symptoms' => ['High blood pressure', 'Headaches', 'Dizziness'],
            'diagnosis' => 'Essential hypertension',
            'treatments' => [
                [
                    'name' => 'Lifestyle modification',
                    'status' => 'ongoing',
                    'start_date' => now(),
                    'end_date' => null,
                    'description' => 'Regular exercise, low-sodium diet, stress management',
                ],
            ],
            'prescriptions' => [
                [
                    'medicine_name' => 'Amlodipine',
                    'dosage' => '5mg',
                    'frequency' => 'Once daily',
                    'duration' => 'Ongoing',
                    'instructions' => 'Take in the morning',
                ],
            ],
        ]);
    }

    /**
     * Configure the model factory to create a record for diabetes.
     */
    public function diabetes(): static
    {
        return $this->state(fn (array $attributes) => [
            'symptoms' => ['Increased thirst', 'Frequent urination', 'Fatigue'],
            'diagnosis' => 'Type 2 Diabetes Mellitus',
            'treatments' => [
                [
                    'name' => 'Blood glucose monitoring',
                    'status' => 'ongoing',
                    'start_date' => now(),
                    'end_date' => null,
                    'description' => 'Monitor blood sugar levels daily',
                ],
                [
                    'name' => 'Dietary management',
                    'status' => 'ongoing',
                    'start_date' => now(),
                    'end_date' => null,
                    'description' => 'Follow diabetic diet plan',
                ],
            ],
            'prescriptions' => [
                [
                    'medicine_name' => 'Metformin',
                    'dosage' => '500mg',
                    'frequency' => 'Twice daily with meals',
                    'duration' => 'Ongoing',
                    'instructions' => 'Take with food to reduce side effects',
                ],
            ],
        ]);
    }

    /**
     * Configure the model factory to create a record for asthma.
     */
    public function asthma(): static
    {
        return $this->state(fn (array $attributes) => [
            'symptoms' => ['Shortness of breath', 'Wheezing', 'Chest tightness'],
            'diagnosis' => 'Bronchial asthma',
            'treatments' => [
                [
                    'name' => 'Avoid triggers',
                    'status' => 'ongoing',
                    'start_date' => now(),
                    'end_date' => null,
                    'description' => 'Identify and avoid asthma triggers',
                ],
            ],
            'prescriptions' => [
                [
                    'medicine_name' => 'Albuterol inhaler',
                    'dosage' => '90mcg',
                    'frequency' => 'As needed',
                    'duration' => 'Ongoing',
                    'instructions' => 'Use 2 puffs every 4-6 hours as needed',
                ],
                [
                    'medicine_name' => 'Fluticasone inhaler',
                    'dosage' => '250mcg',
                    'frequency' => 'Twice daily',
                    'duration' => 'Ongoing',
                    'instructions' => 'Use 2 puffs morning and evening',
                ],
            ],
        ]);
    }

    /**
     * Generate random symptoms.
     */
    private function generateSymptoms(): array
    {
        $commonSymptoms = [
            'Fever', 'Cough', 'Headache', 'Fatigue', 'Nausea',
            'Dizziness', 'Shortness of breath', 'Chest pain',
            'Abdominal pain', 'Joint pain', 'Muscle weakness',
            'Rash', 'Sore throat', 'Runny nose', 'Loss of appetite'
        ];

        return fake()->randomElements($commonSymptoms, fake()->numberBetween(1, 5));
    }

    /**
     * Generate a random diagnosis.
     */
    private function generateDiagnosis(): string
    {
        $diagnoses = [
            'Upper respiratory infection',
            'Hypertension',
            'Type 2 Diabetes',
            'Asthma',
            'Gastroenteritis',
            'Migraine',
            'Anxiety disorder',
            'Depression',
            'Osteoarthritis',
            'Hypothyroidism'
        ];

        return fake()->randomElement($diagnoses);
    }

    /**
     * Generate random treatments.
     */
    private function generateTreatments(): array
    {
        $treatments = [];
        $count = fake()->numberBetween(1, 3);

        for ($i = 0; $i < $count; $i++) {
            $startDate = fake()->dateTimeBetween('-1 month', '+1 month');
            $endDate = fake()->optional(0.7)->dateTimeBetween($startDate, '+6 months');
            
            $treatments[] = [
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

        return $treatments;
    }

    /**
     * Generate random prescriptions.
     */
    private function generatePrescriptions(): array
    {
        $prescriptions = [];
        $count = fake()->numberBetween(1, 3);

        for ($i = 0; $i < $count; $i++) {
            $prescriptions[] = [
                'medicine_name' => fake()->randomElement([
                    'Amoxicillin',
                    'Ibuprofen',
                    'Omeprazole',
                    'Metformin',
                    'Lisinopril',
                    'Atorvastatin',
                    'Albuterol',
                    'Sertraline'
                ]),
                'dosage' => fake()->randomElement(['250mg', '500mg', '1000mg', '10mg', '20mg', '40mg']),
                'frequency' => fake()->randomElement([
                    'Once daily',
                    'Twice daily',
                    'Three times daily',
                    'Every 6 hours',
                    'Every 8 hours',
                    'As needed'
                ]),
                'duration' => fake()->randomElement([
                    '5 days',
                    '7 days',
                    '10 days',
                    '14 days',
                    '30 days',
                    'Ongoing'
                ]),
                'instructions' => fake()->sentence(),
            ];
        }

        return $prescriptions;
    }
} 