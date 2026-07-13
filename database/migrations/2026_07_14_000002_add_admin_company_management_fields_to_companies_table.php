<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table): void {
            if (! Schema::hasColumn('companies', 'verification_status')) {
                $table->string('verification_status', 20)->default('pending')->after('company_description');
                $table->index('verification_status');
            }

            if (! Schema::hasColumn('companies', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('verification_status');
            }

            if (! Schema::hasColumn('companies', 'verified_by')) {
                $table->foreignId('verified_by')->nullable()->after('verified_at')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('companies', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('verified_by');
                $table->index('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table): void {
            if (Schema::hasColumn('companies', 'is_active')) {
                $table->dropIndex(['is_active']);
                $table->dropColumn('is_active');
            }

            if (Schema::hasColumn('companies', 'verified_by')) {
                $table->dropConstrainedForeignId('verified_by');
            }

            if (Schema::hasColumn('companies', 'verified_at')) {
                $table->dropColumn('verified_at');
            }

            if (Schema::hasColumn('companies', 'verification_status')) {
                $table->dropIndex(['verification_status']);
                $table->dropColumn('verification_status');
            }
        });
    }
};
