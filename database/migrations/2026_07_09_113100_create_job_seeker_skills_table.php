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
        Schema::create('job_seeker_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_seeker_profile_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('skill_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('proficiency_level')->nullable();
            $table->unsignedTinyInteger('years_of_experience')->nullable();
            $table->timestamps();

            $table->index('job_seeker_profile_id');
            $table->index('skill_id');
            $table->unique(['job_seeker_profile_id', 'skill_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_seeker_skills');
    }
};
