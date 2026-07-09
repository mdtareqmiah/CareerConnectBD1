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
        Schema::table('educations', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id']);
            $table->dropColumn('user_id');
            $table->foreignId('job_seeker_profile_id')->nullable()->after('id')->constrained('job_seeker_profiles')->cascadeOnDelete()->cascadeOnUpdate();
            $table->index('job_seeker_profile_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('educations', function (Blueprint $table) {
            $table->dropForeign(['job_seeker_profile_id']);
            $table->dropIndex(['job_seeker_profile_id']);
            $table->dropColumn('job_seeker_profile_id');
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->index('user_id');
        });
    }
};
