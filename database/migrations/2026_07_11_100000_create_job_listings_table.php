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
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->unsignedInteger('vacancy');
            $table->string('job_type');
            $table->string('workplace');
            $table->string('employment_status');
            $table->string('experience_level');
            $table->string('education_level');
            $table->string('salary_type');
            $table->unsignedInteger('salary_min');
            $table->unsignedInteger('salary_max');
            $table->string('location');
            $table->date('deadline');
            $table->text('description');
            $table->text('responsibilities');
            $table->text('requirements');
            $table->text('benefits')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_listings');
    }
};
