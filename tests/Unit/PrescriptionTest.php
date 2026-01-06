<?php

namespace Tests\Unit;

use App\Models\Prescription;
use App\Models\MedicalRecord;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PrescriptionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_prescription_with_factory()
    {
        $prescription = Prescription::factory()->create();
        
        $this->assertModelHasAttributes($prescription, [
            'medicine_name' => $prescription->medicine_name,
            'dosage' => $prescription->dosage,
            'frequency' => $prescription->frequency,
            'duration' => $prescription->duration,
            'instructions' => $prescription->instructions,
        ]);
        
        $this->assertNotNull($prescription->medical_record_id);
    }

    /** @test */
    public function it_can_create_an_antibiotic_prescription()
    {
        $prescription = Prescription::factory()->antibiotic()->create();
        
        $this->assertStringContainsString('Amoxicillin', $prescription->medicine_name);
        $this->assertStringContainsString('Take with food', $prescription->instructions);
    }

    /** @test */
    public function it_can_create_a_pain_medication_prescription()
    {
        $prescription = Prescription::factory()->painMedication()->create();
        
        $this->assertStringContainsString('Ibuprofen', $prescription->medicine_name);
        $this->assertStringContainsString('Take as needed for pain', $prescription->instructions);
    }

    /** @test */
    public function it_can_create_a_blood_pressure_medication_prescription()
    {
        $prescription = Prescription::factory()->bloodPressureMedication()->create();
        
        $this->assertStringContainsString('Lisinopril', $prescription->medicine_name);
        $this->assertStringContainsString('Take in the morning', $prescription->instructions);
    }

    /** @test */
    public function it_can_create_a_diabetes_medication_prescription()
    {
        $prescription = Prescription::factory()->diabetesMedication()->create();
        
        $this->assertStringContainsString('Metformin', $prescription->medicine_name);
        $this->assertStringContainsString('Take with meals', $prescription->instructions);
    }

    /** @test */
    public function it_can_create_a_cholesterol_medication_prescription()
    {
        $prescription = Prescription::factory()->cholesterolMedication()->create();
        
        $this->assertStringContainsString('Atorvastatin', $prescription->medicine_name);
        $this->assertStringContainsString('Take at bedtime', $prescription->instructions);
    }

    /** @test */
    public function it_can_create_a_prescription_for_specific_medical_record()
    {
        $record = MedicalRecord::factory()->create();
        $prescription = Prescription::factory()->forMedicalRecord($record)->create();
        
        $this->assertEquals($record->id, $prescription->medical_record_id);
    }

    /** @test */
    public function it_can_create_a_prescription_with_specific_medicine()
    {
        $medicineName = 'Custom Medicine';
        $prescription = Prescription::factory()->withMedicine($medicineName)->create();
        
        $this->assertEquals($medicineName, $prescription->medicine_name);
    }

    /** @test */
    public function it_can_create_a_prescription_with_specific_dosage()
    {
        $dosage = '500mg';
        $prescription = Prescription::factory()->withDosage($dosage)->create();
        
        $this->assertEquals($dosage, $prescription->dosage);
    }

    /** @test */
    public function it_can_create_a_prescription_with_specific_frequency()
    {
        $frequency = 'Twice daily';
        $prescription = Prescription::factory()->withFrequency($frequency)->create();
        
        $this->assertEquals($frequency, $prescription->frequency);
    }

    /** @test */
    public function it_can_create_a_prescription_with_specific_duration()
    {
        $duration = '10 days';
        $prescription = Prescription::factory()->withDuration($duration)->create();
        
        $this->assertEquals($duration, $prescription->duration);
    }

    /** @test */
    public function it_can_create_a_prescription_with_specific_instructions()
    {
        $instructions = 'Take after meals with plenty of water';
        $prescription = Prescription::factory()->withInstructions($instructions)->create();
        
        $this->assertEquals($instructions, $prescription->instructions);
    }

    /** @test */
    public function it_has_medical_record_relationship()
    {
        $record = MedicalRecord::factory()->create();
        $prescription = Prescription::factory()->forMedicalRecord($record)->create();
        
        $this->assertModelHasRelationships($prescription, [
            'medicalRecord' => MedicalRecord::class,
        ]);
        
        $this->assertEquals($record->id, $prescription->medicalRecord->id);
    }

    /** @test */
    public function it_can_get_formatted_prescription()
    {
        $prescription = Prescription::factory()->create([
            'medicine_name' => 'Amoxicillin',
            'dosage' => '500mg',
            'frequency' => 'Three times daily',
            'duration' => '7 days',
            'instructions' => 'Take with food',
        ]);
        
        $expected = "Amoxicillin 500mg - Three times daily for 7 days. Instructions: Take with food";
        $this->assertEquals($expected, $prescription->formatted_prescription);
    }

    /** @test */
    public function it_can_check_if_prescription_is_antibiotic()
    {
        $antibioticPrescription = Prescription::factory()->antibiotic()->create();
        $painPrescription = Prescription::factory()->painMedication()->create();
        
        $this->assertTrue($antibioticPrescription->isAntibiotic());
        $this->assertFalse($painPrescription->isAntibiotic());
    }

    /** @test */
    public function it_can_check_if_prescription_is_pain_medication()
    {
        $painPrescription = Prescription::factory()->painMedication()->create();
        $antibioticPrescription = Prescription::factory()->antibiotic()->create();
        
        $this->assertTrue($painPrescription->isPainMedication());
        $this->assertFalse($antibioticPrescription->isPainMedication());
    }

    /** @test */
    public function it_can_check_if_prescription_is_blood_pressure_medication()
    {
        $bpPrescription = Prescription::factory()->bloodPressureMedication()->create();
        $diabetesPrescription = Prescription::factory()->diabetesMedication()->create();
        
        $this->assertTrue($bpPrescription->isBloodPressureMedication());
        $this->assertFalse($diabetesPrescription->isBloodPressureMedication());
    }

    /** @test */
    public function it_can_check_if_prescription_is_diabetes_medication()
    {
        $diabetesPrescription = Prescription::factory()->diabetesMedication()->create();
        $cholesterolPrescription = Prescription::factory()->cholesterolMedication()->create();
        
        $this->assertTrue($diabetesPrescription->isDiabetesMedication());
        $this->assertFalse($cholesterolPrescription->isDiabetesMedication());
    }

    /** @test */
    public function it_can_check_if_prescription_is_cholesterol_medication()
    {
        $cholesterolPrescription = Prescription::factory()->cholesterolMedication()->create();
        $bpPrescription = Prescription::factory()->bloodPressureMedication()->create();
        
        $this->assertTrue($cholesterolPrescription->isCholesterolMedication());
        $this->assertFalse($bpPrescription->isCholesterolMedication());
    }
} 