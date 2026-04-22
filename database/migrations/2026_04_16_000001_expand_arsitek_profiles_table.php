<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('arsitek_profiles', function (Blueprint $table): void {
            // Rename old column
            $table->renameColumn('school', 'education_institution');
            
            // Bio & specialization
            $table->text('bio')->nullable()->after('degree_type');
            $table->string('specialization', 100)->nullable()->after('bio');
            $table->unsignedSmallInteger('years_experience')->default(0)->after('specialization');

            // Skills & tools
            $table->json('software_skills')->nullable()->after('years_experience');

            // Professional credentials
            $table->string('license_number', 100)->nullable()->after('software_skills');
            $table->string('external_portfolio_url', 500)->nullable()->after('license_number');

            // File uploads
            $table->string('identity_document_url', 500)->nullable()->after('external_portfolio_url');
            $table->string('license_document_url', 500)->nullable()->after('identity_document_url');

            // Verification
            $table->string('verification_status', 20)->default('pending')->after('license_document_url');
            $table->text('verification_note')->nullable()->after('verification_status');
            $table->timestamp('verified_at')->nullable()->after('verification_note');
        });
    }

    public function down(): void
    {
        Schema::table('arsitek_profiles', function (Blueprint $table): void {
            $table->renameColumn('education_institution', 'school');
            $table->dropColumn([
                'bio',
                'specialization',
                'years_experience',
                'software_skills',
                'license_number',
                'external_portfolio_url',
                'identity_document_url',
                'license_document_url',
                'verification_status',
                'verification_note',
                'verified_at',
            ]);
        });
    }
};
