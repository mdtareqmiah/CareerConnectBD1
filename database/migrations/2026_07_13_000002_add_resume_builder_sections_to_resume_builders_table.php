<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resume_builders', function (Blueprint $table) {
            $table->json('personal_information')->nullable();
            $table->json('education')->nullable();
            $table->json('experience')->nullable();
            $table->json('skills')->nullable();
            $table->json('projects')->nullable();
            $table->json('certifications')->nullable();
            $table->json('languages')->nullable();
            $table->json('references')->nullable();
            $table->json('social_links')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('resume_builders', function (Blueprint $table) {
            $table->dropColumn([
                'personal_information',
                'education',
                'experience',
                'skills',
                'projects',
                'certifications',
                'languages',
                'references',
                'social_links',
            ]);
        });
    }
};
