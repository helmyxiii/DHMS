<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $appointmentDate = fake()->dateTimeBetween('now', '+30 days');
        $isPast = $appointmentDate < now();
        $type = fake()->randomElement(['consultation', 'follow-up', 'regular checkup', 'emergency']);
        
        return [
            'doctor_id' => Doctor::factory(),
            'patient_id' => Patient::factory(),
            'appointment_date' => $appointmentDate,
            'type' => $type,
            'status' => $isPast ? fake()->randomElement(['completed', 'cancelled']) : 'scheduled',
            'reason' => $this->getReasonForType($type),
            'notes' => fake()->optional(0.7)->sentence(),
        ];
    }

    /**
     * Configure the model factory to create a scheduled appointment.
     */
    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'appointment_date' => fake()->dateTimeBetween('+1 day', '+30 days'),
            'status' => 'scheduled',
        ]);
    }

    /**
     * Configure the model factory to create a completed appointment.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'appointment_date' => fake()->dateTimeBetween('-30 days', '-1 day'),
            'status' => 'completed',
        ]);
    }

    /**
     * Configure the model factory to create a cancelled appointment.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'appointment_date' => fake()->dateTimeBetween('-30 days', '+30 days'),
            'status' => 'cancelled',
        ]);
    }

    /**
     * Configure the model factory to create an emergency appointment.
     */
    public function emergency(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'emergency',
            'reason' => 'Emergency medical attention required',
            'notes' => 'Patient requires immediate care',
        ]);
    }

    /**
     * Configure the model factory to create a consultation appointment.
     */
    public function consultation(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'consultation',
            'reason' => 'Initial consultation for medical condition',
        ]);
    }

    /**
     * Configure the model factory to create a follow-up appointment.
     */
    public function followUp(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'follow-up',
            'reason' => 'Follow-up appointment for previous treatment',
        ]);
    }

    /**
     * Configure the model factory to create a regular checkup appointment.
     */
    public function regularCheckup(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'regular checkup',
            'reason' => 'Regular health checkup',
        ]);
    }

    /**
     * Configure the model factory to create an appointment with a specific doctor.
     */
    public function withDoctor(Doctor $doctor): static
    {
        return $this->state(fn (array $attributes) => [
            'doctor_id' => $doctor->id,
        ]);
    }

    /**
     * Configure the model factory to create an appointment with a specific patient.
     */
    public function withPatient(Patient $patient): static
    {
        return $this->state(fn (array $attributes) => [
            'patient_id' => $patient->id,
        ]);
    }

    /**
     * Configure the model factory to create an appointment at a specific date and time.
     */
    public function atDateTime(Carbon $dateTime): static
    {
        return $this->state(fn (array $attributes) => [
            'appointment_date' => $dateTime,
            'status' => $dateTime->isPast() ? 'completed' : 'scheduled',
        ]);
    }

    /**
     * Configure the model factory to create an appointment with a specific reason.
     */
    public function withReason(string $reason): static
    {
        return $this->state(fn (array $attributes) => [
            'reason' => $reason,
        ]);
    }

    /**
     * Configure the model factory to create an appointment with specific notes.
     */
    public function withNotes(string $notes): static
    {
        return $this->state(fn (array $attributes) => [
            'notes' => $notes,
        ]);
    }

    /**
     * Get a reason based on the appointment type.
     */
    private function getReasonForType(string $type): string
    {
        return match($type) {
            'consultation' => 'Initial consultation for medical condition',
            'follow-up' => 'Follow-up appointment for previous treatment',
            'regular checkup' => 'Regular health checkup',
            'emergency' => 'Emergency medical attention required',
            default => 'Medical appointment',
        };
    }
} 