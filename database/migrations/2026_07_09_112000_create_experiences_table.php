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
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_seeker_profile_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('job_title');
            $table->string('company_name');
            $table->string('employment_type');
            $table->string('industry')->nullable();
            $table->string('department')->nullable();
            $table->string('location')->nullable();
            $table->string('workplace_type')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('currently_working')->default(false);
            $table->longText('job_description')->nullable();
            $table->timestamps();

            $table->index('job_seeker_profile_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
