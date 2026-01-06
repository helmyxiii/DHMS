<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Run migrations and seeders for each test
        $this->artisan('migrate:fresh');
        $this->artisan('db:seed');
    }

    /**
     * Assert that a model has the expected attributes.
     */
    protected function assertModelHasAttributes($model, array $attributes): void
    {
        foreach ($attributes as $attribute => $value) {
            $this->assertEquals($value, $model->$attribute, "Model does not have expected value for attribute: {$attribute}");
        }
    }

    /**
     * Assert that a model has the expected relationships.
     */
    protected function assertModelHasRelationships($model, array $relationships): void
    {
        foreach ($relationships as $relationship => $expectedClass) {
            $this->assertInstanceOf($expectedClass, $model->$relationship, "Model does not have expected relationship: {$relationship}");
        }
    }
}
