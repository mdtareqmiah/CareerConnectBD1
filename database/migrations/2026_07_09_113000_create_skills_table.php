<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_seeker_profile_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('skill_name');
            $table->string('proficiency_level');
            $table->decimal('years_of_experience', 3, 1)->nullable();
            $table->text('notes')->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('category')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
