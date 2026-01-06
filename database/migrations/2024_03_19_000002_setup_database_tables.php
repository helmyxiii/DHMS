<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class SetupDatabaseTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, ensure the migrations table exists
        if (!Schema::hasTable('migrations')) {
            Schema::create('migrations', function (Blueprint $table) {
                $table->id();
                $table->string('migration');
                $table->integer('batch');
            });
        }

        // Mark the users table migration as completed
        if (!DB::table('migrations')->where('migration', '2014_10_12_000000_create_users_table')->exists()) {
            DB::table('migrations')->insert([
                'migration' => '2014_10_12_000000_create_users_table',
                'batch' => 1
            ]);
        }

        // Add new columns to existing users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('patient')->after('email');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'license_number')) {
                $table->string('license_number')->nullable()->after('address');
            }
            if (!Schema::hasColumn('users', 'qualification')) {
                $table->text('qualification')->nullable()->after('license_number');
            }
            if (!Schema::hasColumn('users', 'experience')) {
                $table->text('experience')->nullable()->after('qualification');
            }
            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('experience');
            }
            if (!Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('date_of_birth');
            }
            if (!Schema::hasColumn('users', 'medical_history')) {
                $table->text('medical_history')->nullable()->after('gender');
            }
        });

        // Create specialties table if it doesn't exist
        if (!Schema::hasTable('specialties')) {
            Schema::create('specialties', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // Create doctor_specialties pivot table if it doesn't exist
        if (!Schema::hasTable('doctor_specialties')) {
            Schema::create('doctor_specialties', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('specialty_id')->constrained()->onDelete('cascade');
                $table->timestamps();

                $table->unique(['user_id', 'specialty_id']);
            });
        }

        // Mark this migration as completed
        DB::table('migrations')->insert([
            'migration' => '2024_03_19_000002_setup_database_tables',
            'batch' => 1
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'role',
                'phone',
                'address',
                'license_number',
                'qualification',
                'experience',
                'date_of_birth',
                'gender',
                'medical_history'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::dropIfExists('doctor_specialties');
        Schema::dropIfExists('specialties');
    }
} 