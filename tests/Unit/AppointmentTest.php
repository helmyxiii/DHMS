<?php

namespace Tests\Unit;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_appointment_with_factory()
    {
        $appointment = Appointment::factory()->create();
        
        $this->assertModelHasAttributes($appointment, [
            'appointment_date' => $appointment->appointment_date,
            'type' => $appointment->type,
            'status' => $appointment->status,
            'reason' => $appointment->reason,
        ]);
        
        $this->assertNotNull($appointment->doctor_id);
        $this->assertNotNull($appointment->patient_id);
    }

    /** @test */
    public function it_can_create_a_scheduled_appointment()
    {
        $appointment = Appointment::factory()->scheduled()->create();
        
        $this->assertEquals('scheduled', $appointment->status);
        $this->assertTrue($appointment->appointment_date->isFuture());
    }

    /** @test */
    public function it_can_create_a_completed_appointment()
    {
        $appointment = Appointment::factory()->completed()->create();
        
        $this->assertEquals('completed', $appointment->status);
        $this->assertTrue($appointment->appointment_date->isPast());
    }

    /** @test */
    public function it_can_create_a_cancelled_appointment()
    {
        $appointment = Appointment::factory()->cancelled()->create();
        
        $this->assertEquals('cancelled', $appointment->status);
    }

    /** @test */
    public function it_can_create_an_emergency_appointment()
    {
        $appointment = Appointment::factory()->emergency()->create();
        
        $this->assertEquals('emergency', $appointment->type);
        $this->assertStringContainsString('Emergency', $appointment->reason);
    }

    /** @test */
    public function it_can_create_a_consultation_appointment()
    {
        $appointment = Appointment::factory()->consultation()->create();
        
        $this->assertEquals('consultation', $appointment->type);
        $this->assertStringContainsString('Initial consultation', $appointment->reason);
    }

    /** @test */
    public function it_can_create_a_follow_up_appointment()
    {
        $appointment = Appointment::factory()->followUp()->create();
        
        $this->assertEquals('follow-up', $appointment->type);
        $this->assertStringContainsString('Follow-up', $appointment->reason);
    }

    /** @test */
    public function it_can_create_a_regular_checkup_appointment()
    {
        $appointment = Appointment::factory()->regularCheckup()->create();
        
        $this->assertEquals('regular checkup', $appointment->type);
        $this->assertStringContainsString('Regular health checkup', $appointment->reason);
    }

    /** @test */
    public function it_can_create_an_appointment_with_specific_doctor()
    {
        $doctor = Doctor::factory()->create();
        $appointment = Appointment::factory()->withDoctor($doctor)->create();
        
        $this->assertEquals($doctor->id, $appointment->doctor_id);
    }

    /** @test */
    public function it_can_create_an_appointment_with_specific_patient()
    {
        $patient = Patient::factory()->create();
        $appointment = Appointment::factory()->withPatient($patient)->create();
        
        $this->assertEquals($patient->id, $appointment->patient_id);
    }

    /** @test */
    public function it_can_create_an_appointment_with_specific_date_time()
    {
        $dateTime = Carbon::now()->addDays(2)->setHour(14)->setMinute(30);
        $appointment = Appointment::factory()->atDateTime($dateTime)->create();
        
        $this->assertEquals($dateTime->format('Y-m-d H:i:s'), $appointment->appointment_date->format('Y-m-d H:i:s'));
    }

    /** @test */
    public function it_can_create_an_appointment_with_specific_reason()
    {
        $reason = 'Annual physical examination';
        $appointment = Appointment::factory()->withReason($reason)->create();
        
        $this->assertEquals($reason, $appointment->reason);
    }

    /** @test */
    public function it_can_create_an_appointment_with_specific_notes()
    {
        $notes = 'Patient requested morning appointment';
        $appointment = Appointment::factory()->withNotes($notes)->create();
        
        $this->assertEquals($notes, $appointment->notes);
    }

    /** @test */
    public function it_has_doctor_relationship()
    {
        $doctor = Doctor::factory()->create();
        $appointment = Appointment::factory()->withDoctor($doctor)->create();
        
        $this->assertModelHasRelationships($appointment, [
            'doctor' => Doctor::class,
        ]);
        
        $this->assertEquals($doctor->id, $appointment->doctor->id);
    }

    /** @test */
    public function it_has_patient_relationship()
    {
        $patient = Patient::factory()->create();
        $appointment = Appointment::factory()->withPatient($patient)->create();
        
        $this->assertModelHasRelationships($appointment, [
            'patient' => Patient::class,
        ]);
        
        $this->assertEquals($patient->id, $appointment->patient->id);
    }

    /** @test */
    public function it_has_medical_record_relationship()
    {
        $appointment = Appointment::factory()->create();
        $medicalRecord = $appointment->medicalRecord()->create([
            'doctor_id' => $appointment->doctor_id,
            'patient_id' => $appointment->patient_id,
            'symptoms' => 'Test symptoms',
            'diagnosis' => 'Test diagnosis',
            'notes' => 'Test notes',
        ]);
        
        $this->assertModelHasRelationships($appointment, [
            'medicalRecord' => MedicalRecord::class,
        ]);
        
        $this->assertEquals($medicalRecord->id, $appointment->medicalRecord->id);
    }

    /** @test */
    public function it_can_check_if_appointment_is_past()
    {
        $pastAppointment = Appointment::factory()->completed()->create();
        $futureAppointment = Appointment::factory()->scheduled()->create();
        
        $this->assertTrue($pastAppointment->isPast());
        $this->assertFalse($futureAppointment->isPast());
    }

    /** @test */
    public function it_can_check_if_appointment_is_future()
    {
        $pastAppointment = Appointment::factory()->completed()->create();
        $futureAppointment = Appointment::factory()->scheduled()->create();
        
        $this->assertFalse($pastAppointment->isFuture());
        $this->assertTrue($futureAppointment->isFuture());
    }

    /** @test */
    public function it_can_check_if_appointment_is_emergency()
    {
        $emergencyAppointment = Appointment::factory()->emergency()->create();
        $regularAppointment = Appointment::factory()->consultation()->create();
        
        $this->assertTrue($emergencyAppointment->isEmergency());
        $this->assertFalse($regularAppointment->isEmergency());
    }
} 