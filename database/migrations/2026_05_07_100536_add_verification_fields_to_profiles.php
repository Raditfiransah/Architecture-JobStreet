<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->string('npwp_document_url', 500)->nullable()->after('identity_document_url');
            $table->string('akta_document_url', 500)->nullable()->after('npwp_document_url');
            $table->string('siup_document_url', 500)->nullable()->after('akta_document_url');
            $table->string('pic_document_url', 500)->nullable()->after('siup_document_url');
        });

        Schema::table('client_profiles', function (Blueprint $table) {
            $table->string('identity_document_url', 500)->nullable()->after('budget_range');
            $table->string('domicile_document_url', 500)->nullable()->after('identity_document_url');
            $table->string('project_ownership_document_url', 500)->nullable()->after('domicile_document_url');
            
            $table->string('verification_status', 20)->default('unverified')->after('project_ownership_document_url');
            $table->text('verification_note')->nullable()->after('verification_status');
            $table->timestamp('verified_at')->nullable()->after('verification_note');
        });
    }

    public function down(): void
    {
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'npwp_document_url',
                'akta_document_url',
                'siup_document_url',
                'pic_document_url'
            ]);
        });

        Schema::table('client_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'identity_document_url',
                'domicile_document_url',
                'project_ownership_document_url',
                'verification_status',
                'verification_note',
                'verified_at'
            ]);
        });
    }
};
