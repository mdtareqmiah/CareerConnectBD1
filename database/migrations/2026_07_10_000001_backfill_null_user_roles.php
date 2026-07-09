<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $jobSeekerRole = Role::where('slug', 'job-seeker')->first();

        if (! $jobSeekerRole) {
            return;
        }

        DB::table('users')
            ->whereNull('role_id')
            ->update(['role_id' => $jobSeekerRole->id]);
    }

    public function down(): void
    {
        // No-op to preserve data safety.
    }
};
