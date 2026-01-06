<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reports')) {
            Schema::create('reports', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('type');
                $table->json('data');
                $table->foreignId('generated_by')->constrained('users')->onDelete('cascade');
                $table->foreignId('role_id')->constrained()->onDelete('cascade');
                $table->string('status')->default('pending');
                $table->timestamp('generated_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
}; 