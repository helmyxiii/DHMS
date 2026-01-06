<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Treatment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MedicalRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = Doctor::all();
        $patients = Patient::all();
        $completedAppointments = Appointment::where('status', 'completed')->get();

        // Create medical records for completed appointments
        foreach ($completedAppointments as $appointment) {
            $medicalRecord = MedicalRecord::create([
                'doctor_id' => $appointment->doctor_id,
                'patient_id' => $appointment->patient_id,
                'appointment_id' => $appointment->id,
                'record_type' => 'appointment',
                'symptoms' => $this->getRandomSymptoms(),
                'diagnosis' => $this->getRandomDiagnosis(),
                'notes' => rand(0, 1) ? "Follow-up recommended in 2 weeks" : null,
                'record_date' => $appointment->appointment_date,
            ]);

            // Add treatments
            $this->createTreatments($medicalRecord);

            // Add prescriptions
            $this->createPrescriptions($medicalRecord);
        }

        // Create some additional medical records without appointments
        for ($i = 0; $i < 5; $i++) {
            $doctor = $doctors->random();
            $patient = $patients->random();

            // Check if the patient exists
            if ($patient) {
                $user = \App\Models\User::find($patient->id);
                if ($user) {
                    $medicalRecord = MedicalRecord::create([
                        'doctor_id' => $doctor->id,
                        'patient_id' => $patient->id,
                        'record_type' => 'checkup',
                        'symptoms' => $this->getRandomSymptoms(),
                        'diagnosis' => $this->getRandomDiagnosis(),
                        'notes' => rand(0, 1) ? "Regular checkup completed" : null,
                        'record_date' => now(),
                    ]);

                    // Add treatments
                    $this->createTreatments($medicalRecord);

                    // Add prescriptions
                    $this->createPrescriptions($medicalRecord);
                } else {
                    // Log or handle the case where the user does not exist
                    \Log::warning("User with ID {$patient->id} does not exist. Skipping medical record creation.");
                }
            } else {
                // Log or handle the case where the patient does not exist
                \Log::warning("Patient with ID {$patient->id} does not exist. Skipping medical record creation.");
            }
        }
    }

    private function getRandomSymptoms(): string
    {
        $symptoms = [
            'Fever, cough, and fatigue',
            'Headache and dizziness',
            'Chest pain and shortness of breath',
            'Abdominal pain and nausea',
            'Joint pain and stiffness',
            'Rash and itching',
            'Sore throat and runny nose',
            'Back pain and muscle weakness',
        ];

        return $symptoms[array_rand($symptoms)];
    }

    private function getRandomDiagnosis(): string
    {
        $diagnoses = [
            'Upper respiratory infection',
            'Hypertension',
            'Type 2 Diabetes',
            'Migraine',
            'Gastroenteritis',
            'Arthritis',
            'Anxiety disorder',
            'Seasonal allergies',
        ];

        return $diagnoses[array_rand($diagnoses)];
    }

    private function createTreatments(MedicalRecord $medicalRecord): void
    {
        $treatments = [
            [
                'name' => 'Physical Therapy',
                'status' => 'completed',
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->subDays(7),
                'description' => 'Weekly sessions for 4 weeks',
            ],
            [
                'name' => 'Medication',
                'status' => 'in_progress',
                'start_date' => Carbon::now()->subDays(14),
                'end_date' => null,
                'description' => 'Daily medication as prescribed',
            ],
            [
                'name' => 'Lifestyle Changes',
                'status' => 'pending',
                'start_date' => Carbon::now(),
                'end_date' => null,
                'description' => 'Diet modification and exercise routine',
            ],
        ];

        // Randomly select 1-3 treatments
        $selectedTreatments = array_rand($treatments, rand(1, 3));
        if (!is_array($selectedTreatments)) {
            $selectedTreatments = [$selectedTreatments];
        }

        foreach ($selectedTreatments as $index) {
            Treatment::create([
                'medical_record_id' => $medicalRecord->id,
                'treatment_name' => $treatments[$index]['name'],
                'status' => $treatments[$index]['status'],
                'start_date' => $treatments[$index]['start_date'],
                'end_date' => $treatments[$index]['end_date'],
                'description' => $treatments[$index]['description'],
            ]);
        }
    }

    private function createPrescriptions(MedicalRecord $medicalRecord): void
    {
        $prescriptions = [
            [
                'medicine_name' => 'Amoxicillin',
                'dosage' => '500mg',
                'frequency' => 'Three times daily',
                'duration' => '7 days',
                'instructions' => 'Take with food',
            ],
            [
                'medicine_name' => 'Ibuprofen',
                'dosage' => '400mg',
                'frequency' => 'Every 6 hours',
                'duration' => '5 days',
                'instructions' => 'Take as needed for pain',
            ],
            [
                'medicine_name' => 'Omeprazole',
                'dosage' => '20mg',
                'frequency' => 'Once daily',
                'duration' => '30 days',
                'instructions' => 'Take before breakfast',
            ],
            [
                'medicine_name' => 'Cetirizine',
                'dosage' => '10mg',
                'frequency' => 'Once daily',
                'duration' => '14 days',
                'instructions' => 'Take at bedtime',
            ],
        ];

        // Randomly select 1-2 prescriptions
        $selectedPrescriptions = array_rand($prescriptions, rand(1, 2));
        if (!is_array($selectedPrescriptions)) {
            $selectedPrescriptions = [$selectedPrescriptions];
        }

        foreach ($selectedPrescriptions as $index) {
            Prescription::create([
                'medical_record_id' => $medicalRecord->id,
                'medicine_name' => $prescriptions[$index]['medicine_name'],
                'dosage' => $prescriptions[$index]['dosage'],
                'frequency' => $prescriptions[$index]['frequency'],
                'duration' => $prescriptions[$index]['duration'],
                'instructions' => $prescriptions[$index]['instructions'],
            ]);
        }
    }
} 