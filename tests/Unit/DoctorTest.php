<?php

namespace Tests\Unit;

use App\Models\Doctor;
use App\Models\User;
use App\Models\Specialty;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DoctorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_doctor_with_factory()
    {
        $doctor = Doctor::factory()->create();
        
        $this->assertModelHasAttributes($doctor, [
            'license_number' => $doctor->license_number,
            'qualifications' => $doctor->qualifications,
            'experience' => $doctor->experience,
        ]);
        
        $this->assertNotNull($doctor->user_id);
        $this->assertNotNull($doctor->specialty_id);
    }

    /** @test */
    public function it_can_create_a_doctor_with_specific_specialty()
    {
        $specialty = Specialty::factory()->create();
        $doctor = Doctor::factory()->withSpecialty($specialty)->create();
        
        $this->assertEquals($specialty->id, $doctor->specialty_id);
    }

    /** @test */
    public function it_can_create_a_doctor_with_specific_user()
    {
        $user = User::factory()->doctor()->create();
        $doctor = Doctor::factory()->withUser($user)->create();
        
        $this->assertEquals($user->id, $doctor->user_id);
    }

    /** @test */
    public function it_can_create_a_doctor_with_specific_qualifications()
    {
        $qualifications = 'MD, PhD, FACC';
        $doctor = Doctor::factory()->withQualifications($qualifications)->create();
        
        $this->assertEquals($qualifications, $doctor->qualifications);
    }

    /** @test */
    public function it_can_create_a_doctor_with_specific_experience()
    {
        $years = 15;
        $doctor = Doctor::factory()->withExperience($years)->create();
        
        $this->assertEquals("{$years} years of experience", $doctor->experience);
    }

    /** @test */
    public function it_can_create_a_doctor_with_specific_license_number()
    {
        $licenseNumber = 'MD123456';
        $doctor = Doctor::factory()->withLicenseNumber($licenseNumber)->create();
        
        $this->assertEquals($licenseNumber, $doctor->license_number);
    }

    /** @test */
    public function it_can_create_a_new_doctor()
    {
        $doctor = Doctor::factory()->newDoctor()->create();
        
        $this->assertStringContainsString('years of experience', $doctor->experience);
        $this->assertStringContainsString('MD', $doctor->qualifications);
        $this->assertStringContainsString('Residency Completed', $doctor->qualifications);
    }

    /** @test */
    public function it_can_create_a_senior_doctor()
    {
        $doctor = Doctor::factory()->seniorDoctor()->create();
        
        $this->assertStringContainsString('years of experience', $doctor->experience);
        $this->assertStringContainsString('MD', $doctor->qualifications);
        $this->assertStringContainsString('Board Certified', $doctor->qualifications);
    }

    /** @test */
    public function it_has_user_relationship()
    {
        $user = User::factory()->doctor()->create();
        $doctor = Doctor::factory()->withUser($user)->create();
        
        $this->assertModelHasRelationships($doctor, [
            'user' => User::class,
        ]);
        
        $this->assertEquals($user->id, $doctor->user->id);
    }

    /** @test */
    public function it_has_specialty_relationship()
    {
        $specialty = Specialty::factory()->create();
        $doctor = Doctor::factory()->withSpecialty($specialty)->create();
        
        $this->assertModelHasRelationships($doctor, [
            'specialty' => Specialty::class,
        ]);
        
        $this->assertEquals($specialty->id, $doctor->specialty->id);
    }

    /** @test */
    public function it_has_appointments_relationship()
    {
        $doctor = Doctor::factory()->create();
        $appointments = Appointment::factory()->withDoctor($doctor)->count(3)->create();
        
        $this->assertModelHasRelationships($doctor, [
            'appointments' => Appointment::class,
        ]);
        
        $this->assertCount(3, $doctor->appointments);
        $this->assertTrue($doctor->appointments->contains($appointments->first()));
    }

    /** @test */
    public function it_has_medical_records_relationship()
    {
        $doctor = Doctor::factory()->create();
        $records = MedicalRecord::factory()->withDoctor($doctor)->count(3)->create();
        
        $this->assertModelHasRelationships($doctor, [
            'medicalRecords' => MedicalRecord::class,
        ]);
        
        $this->assertCount(3, $doctor->medicalRecords);
        $this->assertTrue($doctor->medicalRecords->contains($records->first()));
    }

    /** @test */
    public function it_can_get_full_name()
    {
        $user = User::factory()->doctor()->withName('John Smith')->create();
        $doctor = Doctor::factory()->withUser($user)->create();
        
        $this->assertEquals('Dr. John Smith', $doctor->full_name);
    }

    /** @test */
    public function it_can_get_specialty_name()
    {
        $specialty = Specialty::factory()->create(['name' => 'Cardiology']);
        $doctor = Doctor::factory()->withSpecialty($specialty)->create();
        
        $this->assertEquals('Cardiology', $doctor->specialty_name);
    }
} 