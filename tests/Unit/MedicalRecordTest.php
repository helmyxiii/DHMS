<?php

namespace Tests\Unit;

use App\Models\MedicalRecord;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Treatment;
use App\Models\Prescription;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MedicalRecordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_medical_record_with_factory()
    {
        $record = MedicalRecord::factory()->create();
        
        $this->assertModelHasAttributes($record, [
            'symptoms' => $record->symptoms,
            'diagnosis' => $record->diagnosis,
            'notes' => $record->notes,
        ]);
        
        $this->assertNotNull($record->doctor_id);
        $this->assertNotNull($record->patient_id);
    }

    /** @test */
    public function it_can_create_a_medical_record_with_specific_doctor()
    {
        $doctor = Doctor::factory()->create();
        $record = MedicalRecord::factory()->withDoctor($doctor)->create();
        
        $this->assertEquals($doctor->id, $record->doctor_id);
    }

    /** @test */
    public function it_can_create_a_medical_record_with_specific_patient()
    {
        $patient = Patient::factory()->create();
        $record = MedicalRecord::factory()->withPatient($patient)->create();
        
        $this->assertEquals($patient->id, $record->patient_id);
    }

    /** @test */
    public function it_can_create_a_medical_record_with_specific_appointment()
    {
        $appointment = Appointment::factory()->create();
        $record = MedicalRecord::factory()->withAppointment($appointment)->create();
        
        $this->assertEquals($appointment->id, $record->appointment_id);
        $this->assertEquals($appointment->doctor_id, $record->doctor_id);
        $this->assertEquals($appointment->patient_id, $record->patient_id);
    }

    /** @test */
    public function it_can_create_a_medical_record_with_specific_symptoms()
    {
        $symptoms = ['Fever', 'Cough', 'Fatigue'];
        $record = MedicalRecord::factory()->withSymptoms($symptoms)->create();
        
        $this->assertEquals(implode(', ', $symptoms), $record->symptoms);
    }

    /** @test */
    public function it_can_create_a_medical_record_with_specific_diagnosis()
    {
        $diagnosis = 'Acute Bronchitis';
        $record = MedicalRecord::factory()->withDiagnosis($diagnosis)->create();
        
        $this->assertEquals($diagnosis, $record->diagnosis);
    }

    /** @test */
    public function it_can_create_a_medical_record_with_specific_notes()
    {
        $notes = 'Patient shows signs of improvement';
        $record = MedicalRecord::factory()->withNotes($notes)->create();
        
        $this->assertEquals($notes, $record->notes);
    }

    /** @test */
    public function it_can_create_a_medical_record_for_common_cold()
    {
        $record = MedicalRecord::factory()->commonCold()->create();
        
        $this->assertStringContainsString('Runny nose', $record->symptoms);
        $this->assertStringContainsString('Sore throat', $record->symptoms);
        $this->assertStringContainsString('Viral upper respiratory infection', $record->diagnosis);
    }

    /** @test */
    public function it_can_create_a_medical_record_for_hypertension()
    {
        $record = MedicalRecord::factory()->hypertension()->create();
        
        $this->assertStringContainsString('High blood pressure', $record->symptoms);
        $this->assertStringContainsString('Headaches', $record->symptoms);
        $this->assertStringContainsString('Essential hypertension', $record->diagnosis);
    }

    /** @test */
    public function it_can_create_a_medical_record_for_diabetes()
    {
        $record = MedicalRecord::factory()->diabetes()->create();
        
        $this->assertStringContainsString('Increased thirst', $record->symptoms);
        $this->assertStringContainsString('Fatigue', $record->symptoms);
        $this->assertStringContainsString('Type 2 Diabetes Mellitus', $record->diagnosis);
    }

    /** @test */
    public function it_can_create_a_medical_record_for_asthma()
    {
        $record = MedicalRecord::factory()->asthma()->create();
        
        $this->assertStringContainsString('Shortness of breath', $record->symptoms);
        $this->assertStringContainsString('Wheezing', $record->symptoms);
        $this->assertStringContainsString('Bronchial asthma', $record->diagnosis);
    }

    /** @test */
    public function it_has_doctor_relationship()
    {
        $doctor = Doctor::factory()->create();
        $record = MedicalRecord::factory()->withDoctor($doctor)->create();
        
        $this->assertModelHasRelationships($record, [
            'doctor' => Doctor::class,
        ]);
        
        $this->assertEquals($doctor->id, $record->doctor->id);
    }

    /** @test */
    public function it_has_patient_relationship()
    {
        $patient = Patient::factory()->create();
        $record = MedicalRecord::factory()->withPatient($patient)->create();
        
        $this->assertModelHasRelationships($record, [
            'patient' => Patient::class,
        ]);
        
        $this->assertEquals($patient->id, $record->patient->id);
    }

    /** @test */
    public function it_has_appointment_relationship()
    {
        $appointment = Appointment::factory()->create();
        $record = MedicalRecord::factory()->withAppointment($appointment)->create();
        
        $this->assertModelHasRelationships($record, [
            'appointment' => Appointment::class,
        ]);
        
        $this->assertEquals($appointment->id, $record->appointment->id);
    }

    /** @test */
    public function it_has_treatments_relationship()
    {
        $record = MedicalRecord::factory()->create();
        $treatments = Treatment::factory()->forMedicalRecord($record)->count(3)->create();
        
        $this->assertModelHasRelationships($record, [
            'treatments' => Treatment::class,
        ]);
        
        $this->assertCount(3, $record->treatments);
        $this->assertTrue($record->treatments->contains($treatments->first()));
    }

    /** @test */
    public function it_has_prescriptions_relationship()
    {
        $record = MedicalRecord::factory()->create();
        $prescriptions = Prescription::factory()->forMedicalRecord($record)->count(3)->create();
        
        $this->assertModelHasRelationships($record, [
            'prescriptions' => Prescription::class,
        ]);
        
        $this->assertCount(3, $record->prescriptions);
        $this->assertTrue($record->prescriptions->contains($prescriptions->first()));
    }

    /** @test */
    public function it_can_get_formatted_symptoms()
    {
        $symptoms = ['Fever', 'Cough', 'Fatigue'];
        $record = MedicalRecord::factory()->withSymptoms($symptoms)->create();
        
        $this->assertEquals(implode(', ', $symptoms), $record->formatted_symptoms);
    }

    /** @test */
    public function it_can_get_formatted_treatments()
    {
        $record = MedicalRecord::factory()->create();
        $treatments = Treatment::factory()->forMedicalRecord($record)->count(3)->create();
        
        $this->assertCount(3, $record->formatted_treatments);
        $this->assertContains($treatments->first()->name, $record->formatted_treatments);
    }

    /** @test */
    public function it_can_get_formatted_prescriptions()
    {
        $record = MedicalRecord::factory()->create();
        $prescriptions = Prescription::factory()->forMedicalRecord($record)->count(3)->create();
        
        $this->assertCount(3, $record->formatted_prescriptions);
        $this->assertContains($prescriptions->first()->medicine_name, $record->formatted_prescriptions);
    }
} 