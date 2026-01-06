<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ConsolidateDatabaseTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mark all previous migrations as completed
        $migrations = [
            '2014_10_12_000000_create_users_table',
            '2024_03_19_000002_setup_database_tables',
            '2025_05_03_001718_create_specialties_table',
            '2025_05_03_001719_create_users_table',
            '2025_05_03_001719_create_admins_table',
            '2025_05_03_001720_create_doctors_table',
            '2025_05_03_001720_create_patients_table',
            '2025_05_03_001721_create_doctor_specialty_table'
        ];

        foreach ($migrations as $migration) {
            if (!DB::table('migrations')->where('migration', $migration)->exists()) {
                DB::table('migrations')->insert([
                    'migration' => $migration,
                    'batch' => 1
                ]);
            }
        }

        // Create admin table if it doesn't exist
        if (!Schema::hasTable('admins')) {
            Schema::create('admins', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('position')->nullable();
                $table->text('permissions')->nullable();
                $table->timestamps();
            });
        }

        // Create appointments table if it doesn't exist
        if (!Schema::hasTable('appointments')) {
            Schema::create('appointments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
                $table->dateTime('appointment_date');
                $table->string('type')->default('regular');
                $table->string('status')->default('pending');
                $table->text('reason')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // Create medical records table if it doesn't exist
        if (!Schema::hasTable('medical_records')) {
            Schema::create('medical_records', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('appointment_id')->nullable()->constrained('appointments')->onDelete('set null');
                $table->string('record_type');
                $table->text('symptoms')->nullable();
                $table->text('diagnosis')->nullable();
                $table->text('treatment')->nullable();
                $table->text('notes')->nullable();
                $table->date('record_date');
                $table->timestamps();
            });
        }

        // Create treatments table if it doesn't exist
        if (!Schema::hasTable('treatments')) {
            Schema::create('treatments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('medical_record_id')->constrained()->onDelete('cascade');
                $table->string('treatment_name');
                $table->string('status')->default('pending');
                $table->dateTime('start_date');
                $table->dateTime('end_date')->nullable();
                $table->text('description');
                $table->timestamps();
            });
        }

        // Create prescriptions table if it doesn't exist
        if (!Schema::hasTable('prescriptions')) {
            Schema::create('prescriptions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('medical_record_id')->constrained()->onDelete('cascade');
                $table->string('medicine_name');
                $table->string('dosage');
                $table->string('frequency');
                $table->string('duration');
                $table->string('instructions');
                $table->timestamps();
            });
        }

        // Create health tips table if it doesn't exist
        if (!Schema::hasTable('health_tips')) {
            Schema::create('health_tips', function (Blueprint $table) {
                $table->id();
                $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
                $table->string('title');
                $table->text('content');
                $table->string('category')->nullable();
                $table->boolean('is_published')->default(false);
                $table->timestamps();
            });
        }

        // Create schedules table if it doesn't exist
        if (!Schema::hasTable('schedules')) {
            Schema::create('schedules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
                $table->date('date');
                $table->time('start_time');
                $table->time('end_time');
                $table->boolean('is_available')->default(true);
                $table->timestamps();
            });
        }

        // Create reports table if it doesn't exist - COMMENTED OUT TO AVOID CONFLICT
        /*
        if (!Schema::hasTable('reports')) {
            Schema::create('reports', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
                $table->string('report_type');
                $table->text('content');
                $table->date('report_date');
                $table->string('status')->default('pending');
                $table->timestamps();
            });
        }
        */

        // Mark this migration as completed
        DB::table('migrations')->insert([
            'migration' => '2024_03_19_000003_consolidate_database_tables',
            'batch' => 1
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('health_tips');
        Schema::dropIfExists('medical_records');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('prescriptions');
        Schema::dropIfExists('treatments');
        Schema::dropIfExists('admins');
    }
} 