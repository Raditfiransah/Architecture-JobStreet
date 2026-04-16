<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_profiles', function (Blueprint $table): void {
            // Contact
            $table->string('phone', 30)->nullable()->after('location');

            // Company details
            $table->json('business_fields')->nullable()->after('phone');
            $table->unsignedSmallInteger('founded_year')->nullable()->after('business_fields');
            $table->string('nib_number', 100)->nullable()->after('founded_year');

            // File uploads
            $table->string('identity_document_url', 500)->nullable()->after('nib_number');

            // Verification
            $table->string('verification_status', 20)->default('pending')->after('identity_document_url');
            $table->text('verification_note')->nullable()->after('verification_status');
        });
    }

    public function down(): void
    {
        Schema::table('company_profiles', function (Blueprint $table): void {
            $table->dropColumn([
                'phone',
                'business_fields',
                'founded_year',
                'nib_number',
                'identity_document_url',
                'verification_status',
                'verification_note',
            ]);
        });
    }
};
