<?php

namespace Tests\Unit;

use App\Models\Treatment;
use App\Models\MedicalRecord;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class TreatmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_treatment_with_factory()
    {
        $treatment = Treatment::factory()->create();
        
        $this->assertModelHasAttributes($treatment, [
            'name' => $treatment->name,
            'description' => $treatment->description,
            'status' => $treatment->status,
            'start_date' => $treatment->start_date,
        ]);
        
        $this->assertNotNull($treatment->medical_record_id);
    }

    /** @test */
    public function it_can_create_an_ongoing_treatment()
    {
        $treatment = Treatment::factory()->ongoing()->create();
        
        $this->assertEquals('ongoing', $treatment->status);
        $this->assertNotNull($treatment->start_date);
        $this->assertNull($treatment->end_date);
    }

    /** @test */
    public function it_can_create_a_completed_treatment()
    {
        $treatment = Treatment::factory()->completed()->create();
        
        $this->assertEquals('completed', $treatment->status);
        $this->assertNotNull($treatment->start_date);
        $this->assertNotNull($treatment->end_date);
        $this->assertTrue($treatment->end_date->isAfter($treatment->start_date));
    }

    /** @test */
    public function it_can_create_a_cancelled_treatment()
    {
        $treatment = Treatment::factory()->cancelled()->create();
        
        $this->assertEquals('cancelled', $treatment->status);
    }

    /** @test */
    public function it_can_create_a_medication_therapy_treatment()
    {
        $treatment = Treatment::factory()->medicationTherapy()->create();
        
        $this->assertEquals('Medication Therapy', $treatment->name);
        $this->assertStringContainsString('prescribed medications', $treatment->description);
    }

    /** @test */
    public function it_can_create_a_physical_therapy_treatment()
    {
        $treatment = Treatment::factory()->physicalTherapy()->create();
        
        $this->assertEquals('Physical Therapy', $treatment->name);
        $this->assertStringContainsString('physical exercises', $treatment->description);
    }

    /** @test */
    public function it_can_create_a_lifestyle_modification_treatment()
    {
        $treatment = Treatment::factory()->lifestyleModification()->create();
        
        $this->assertEquals('Lifestyle Modification', $treatment->name);
        $this->assertStringContainsString('lifestyle changes', $treatment->description);
    }

    /** @test */
    public function it_can_create_a_dietary_changes_treatment()
    {
        $treatment = Treatment::factory()->dietaryChanges()->create();
        
        $this->assertEquals('Dietary Changes', $treatment->name);
        $this->assertStringContainsString('dietary modifications', $treatment->description);
    }

    /** @test */
    public function it_can_create_an_exercise_program_treatment()
    {
        $treatment = Treatment::factory()->exerciseProgram()->create();
        
        $this->assertEquals('Exercise Program', $treatment->name);
        $this->assertStringContainsString('exercise routine', $treatment->description);
    }

    /** @test */
    public function it_can_create_a_counseling_treatment()
    {
        $treatment = Treatment::factory()->counseling()->create();
        
        $this->assertEquals('Counseling', $treatment->name);
        $this->assertStringContainsString('psychological support', $treatment->description);
    }

    /** @test */
    public function it_can_create_a_surgery_treatment()
    {
        $treatment = Treatment::factory()->surgery()->create();
        
        $this->assertEquals('Surgery', $treatment->name);
        $this->assertStringContainsString('surgical procedure', $treatment->description);
    }

    /** @test */
    public function it_can_create_a_rest_and_recovery_treatment()
    {
        $treatment = Treatment::factory()->restAndRecovery()->create();
        
        $this->assertEquals('Rest and Recovery', $treatment->name);
        $this->assertStringContainsString('rest period', $treatment->description);
    }

    /** @test */
    public function it_can_create_a_treatment_for_specific_medical_record()
    {
        $record = MedicalRecord::factory()->create();
        $treatment = Treatment::factory()->forMedicalRecord($record)->create();
        
        $this->assertEquals($record->id, $treatment->medical_record_id);
    }

    /** @test */
    public function it_can_create_a_treatment_with_specific_name()
    {
        $name = 'Custom Treatment';
        $treatment = Treatment::factory()->withName($name)->create();
        
        $this->assertEquals($name, $treatment->name);
    }

    /** @test */
    public function it_can_create_a_treatment_with_specific_description()
    {
        $description = 'Custom treatment description';
        $treatment = Treatment::factory()->withDescription($description)->create();
        
        $this->assertEquals($description, $treatment->description);
    }

    /** @test */
    public function it_can_create_a_treatment_with_specific_dates()
    {
        $startDate = Carbon::now();
        $endDate = Carbon::now()->addDays(7);
        $treatment = Treatment::factory()->withDates($startDate, $endDate)->create();
        
        $this->assertEquals($startDate->format('Y-m-d'), $treatment->start_date->format('Y-m-d'));
        $this->assertEquals($endDate->format('Y-m-d'), $treatment->end_date->format('Y-m-d'));
    }

    /** @test */
    public function it_has_medical_record_relationship()
    {
        $record = MedicalRecord::factory()->create();
        $treatment = Treatment::factory()->forMedicalRecord($record)->create();
        
        $this->assertModelHasRelationships($treatment, [
            'medicalRecord' => MedicalRecord::class,
        ]);
        
        $this->assertEquals($record->id, $treatment->medicalRecord->id);
    }

    /** @test */
    public function it_can_check_if_treatment_is_active()
    {
        $ongoingTreatment = Treatment::factory()->ongoing()->create();
        $completedTreatment = Treatment::factory()->completed()->create();
        $cancelledTreatment = Treatment::factory()->cancelled()->create();
        
        $this->assertTrue($ongoingTreatment->isActive());
        $this->assertFalse($completedTreatment->isActive());
        $this->assertFalse($cancelledTreatment->isActive());
    }

    /** @test */
    public function it_can_check_if_treatment_is_completed()
    {
        $ongoingTreatment = Treatment::factory()->ongoing()->create();
        $completedTreatment = Treatment::factory()->completed()->create();
        
        $this->assertFalse($ongoingTreatment->isCompleted());
        $this->assertTrue($completedTreatment->isCompleted());
    }

    /** @test */
    public function it_can_check_if_treatment_is_cancelled()
    {
        $ongoingTreatment = Treatment::factory()->ongoing()->create();
        $cancelledTreatment = Treatment::factory()->cancelled()->create();
        
        $this->assertFalse($ongoingTreatment->isCancelled());
        $this->assertTrue($cancelledTreatment->isCancelled());
    }

    /** @test */
    public function it_can_get_duration()
    {
        $startDate = Carbon::now();
        $endDate = Carbon::now()->addDays(7);
        $treatment = Treatment::factory()->withDates($startDate, $endDate)->create();
        
        $this->assertEquals(7, $treatment->duration);
    }
} 