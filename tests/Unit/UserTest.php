<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_user_with_factory()
    {
        $user = User::factory()->create();
        
        $this->assertModelHasAttributes($user, [
            'email' => $user->email,
        ]);
        
        $this->assertNotNull($user->name);
        $this->assertNotNull($user->email_verified_at);
        $this->assertTrue(Hash::check('password', $user->password));
    }

    /** @test */
    public function it_can_create_an_admin_user()
    {
        $admin = User::factory()->admin()->create();
        
        $this->assertStringStartsWith('Admin', $admin->name);
    }

    /** @test */
    public function it_can_create_a_doctor_user()
    {
        $doctor = User::factory()->doctor()->create();
        
        $this->assertStringStartsWith('Dr.', $doctor->name);
    }

    /** @test */
    public function it_can_create_a_patient_user()
    {
        $patient = User::factory()->patient()->create();
    }

    /** @test */
    public function it_can_create_an_unverified_user()
    {
        $user = User::factory()->unverified()->create();
        
        $this->assertNull($user->email_verified_at);
    }

    /** @test */
    public function it_can_create_a_user_with_custom_password()
    {
        $password = 'custom-password';
        $user = User::factory()->withPassword($password)->create();
        
        $this->assertTrue(Hash::check($password, $user->password));
    }

    /** @test */
    public function it_can_create_a_user_with_custom_name()
    {
        $name = 'John Doe';
        $user = User::factory()->withName($name)->create();
        
        $this->assertEquals($name, $user->name);
    }

    /** @test */
    public function it_can_create_a_user_with_custom_email()
    {
        $email = 'john@example.com';
        $user = User::factory()->withEmail($email)->create();
        
        $this->assertEquals($email, $user->email);
    }

    /** @test */
    public function it_has_doctor_relationship()
    {
        $user = User::factory()->doctor()->create();
        $doctor = Doctor::factory()->withUser($user)->create();
        
        $this->assertModelHasRelationships($user, [
            'doctor' => Doctor::class,
        ]);
        
        $this->assertEquals($doctor->id, $user->doctor->id);
    }

    /** @test */
    public function it_has_patient_relationship()
    {
        $user = User::factory()->patient()->create();
        $patient = Patient::factory()->withUser($user)->create();
        
        $this->assertModelHasRelationships($user, [
            'patient' => Patient::class,
        ]);
        
        $this->assertEquals($patient->id, $user->patient->id);
    }

    /** @test */
    public function it_can_check_if_user_is_admin()
    {
        $admin = User::factory()->admin()->create();
        $doctor = User::factory()->doctor()->create();
        $patient = User::factory()->patient()->create();
        
        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($doctor->isAdmin());
        $this->assertFalse($patient->isAdmin());
    }

    /** @test */
    public function it_can_check_if_user_is_doctor()
    {
        $admin = User::factory()->admin()->create();
        $doctor = User::factory()->doctor()->create();
        $patient = User::factory()->patient()->create();
        
        $this->assertFalse($admin->isDoctor());
        $this->assertTrue($doctor->isDoctor());
        $this->assertFalse($patient->isDoctor());
    }

    /** @test */
    public function it_can_check_if_user_is_patient()
    {
        $admin = User::factory()->admin()->create();
        $doctor = User::factory()->doctor()->create();
        $patient = User::factory()->patient()->create();
        
        $this->assertFalse($admin->isPatient());
        $this->assertFalse($doctor->isPatient());
        $this->assertTrue($patient->isPatient());
    }
} 