<?php

namespace Tests\Unit;

use App\Models\Patient;
use App\Models\User;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PatientTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_patient_with_factory()
    {
        $patient = Patient::factory()->create();
        
        $this->assertModelHasAttributes($patient, [
            'date_of_birth' => $patient->date_of_birth,
            'gender' => $patient->gender,
            'phone' => $patient->phone,
            'address' => $patient->address,
            'medical_history' => $patient->medical_history,
        ]);
        
        $this->assertNotNull($patient->user_id);
    }

    /** @test */
    public function it_can_create_a_patient_with_specific_user()
    {
        $user = User::factory()->patient()->create();
        $patient = Patient::factory()->withUser($user)->create();
        
        $this->assertEquals($user->id, $patient->user_id);
    }

    /** @test */
    public function it_can_create_a_healthy_patient()
    {
        $patient = Patient::factory()->healthy()->create();
        
        $this->assertEmpty($patient->medical_history);
    }

    /** @test */
    public function it_can_create_a_patient_with_chronic_conditions()
    {
        $patient = Patient::factory()->withChronicConditions()->create();
        
        $this->assertNotEmpty($patient->medical_history);
        $this->assertStringContainsString('Hypertension', $patient->medical_history);
        $this->assertStringContainsString('Diabetes', $patient->medical_history);
    }

    /** @test */
    public function it_can_create_a_patient_with_specific_age()
    {
        $age = 25;
        $patient = Patient::factory()->withAge($age)->create();
        
        $this->assertEquals($age, $patient->age);
    }

    /** @test */
    public function it_can_create_a_patient_with_specific_gender()
    {
        $gender = 'Female';
        $patient = Patient::factory()->withGender($gender)->create();
        
        $this->assertEquals($gender, $patient->gender);
    }

    /** @test */
    public function it_can_create_a_patient_with_specific_phone()
    {
        $phone = '+1234567890';
        $patient = Patient::factory()->withPhone($phone)->create();
        
        $this->assertEquals($phone, $patient->phone);
    }

    /** @test */
    public function it_can_create_a_patient_with_specific_address()
    {
        $address = '123 Main St, City, Country';
        $patient = Patient::factory()->withAddress($address)->create();
        
        $this->assertEquals($address, $patient->address);
    }

    /** @test */
    public function it_can_create_a_pediatric_patient()
    {
        $patient = Patient::factory()->pediatric()->create();
        
        $this->assertLessThan(18, $patient->age);
    }

    /** @test */
    public function it_can_create_a_geriatric_patient()
    {
        $patient = Patient::factory()->geriatric()->create();
        
        $this->assertGreaterThan(65, $patient->age);
    }

    /** @test */
    public function it_can_create_a_patient_with_specific_condition()
    {
        $condition = 'Asthma';
        $patient = Patient::factory()->withCondition($condition)->create();
        
        $this->assertStringContainsString($condition, $patient->medical_history);
    }

    /** @test */
    public function it_can_create_a_patient_with_multiple_conditions()
    {
        $conditions = ['Asthma', 'Allergies', 'Migraine'];
        $patient = Patient::factory()->withConditions($conditions)->create();
        
        foreach ($conditions as $condition) {
            $this->assertStringContainsString($condition, $patient->medical_history);
        }
    }

    /** @test */
    public function it_has_user_relationship()
    {
        $user = User::factory()->patient()->create();
        $patient = Patient::factory()->withUser($user)->create();
        
        $this->assertModelHasRelationships($patient, [
            'user' => User::class,
        ]);
        
        $this->assertEquals($user->id, $patient->user->id);
    }

    /** @test */
    public function it_has_appointments_relationship()
    {
        $patient = Patient::factory()->create();
        $appointments = Appointment::factory()->withPatient($patient)->count(3)->create();
        
        $this->assertModelHasRelationships($patient, [
            'appointments' => Appointment::class,
        ]);
        
        $this->assertCount(3, $patient->appointments);
        $this->assertTrue($patient->appointments->contains($appointments->first()));
    }

    /** @test */
    public function it_has_medical_records_relationship()
    {
        $patient = Patient::factory()->create();
        $records = MedicalRecord::factory()->withPatient($patient)->count(3)->create();
        
        $this->assertModelHasRelationships($patient, [
            'medicalRecords' => MedicalRecord::class,
        ]);
        
        $this->assertCount(3, $patient->medicalRecords);
        $this->assertTrue($patient->medicalRecords->contains($records->first()));
    }

    /** @test */
    public function it_can_get_full_name()
    {
        $user = User::factory()->patient()->withName('Jane Doe')->create();
        $patient = Patient::factory()->withUser($user)->create();
        
        $this->assertEquals('Jane Doe', $patient->full_name);
    }

    /** @test */
    public function it_can_get_age()
    {
        $patient = Patient::factory()->withAge(30)->create();
        
        $this->assertEquals(30, $patient->age);
    }
} 