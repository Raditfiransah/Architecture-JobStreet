<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Fix existing rows that were set to 'pending' by the old default
     * but never actually submitted any documents.
     *
     * A profile is considered "never submitted" when ALL document URLs are null.
     */
    public function up(): void
    {
        // Arsitek: reset to unverified if no documents uploaded
        DB::table('arsitek_profiles')
            ->where('verification_status', 'pending')
            ->whereNull('identity_document_url')
            ->whereNull('license_document_url')
            ->update(['verification_status' => 'unverified']);

        // Company: reset to unverified if no documents uploaded
        DB::table('company_profiles')
            ->where('verification_status', 'pending')
            ->whereNull('identity_document_url')
            ->update(['verification_status' => 'unverified']);
    }

    public function down(): void
    {
        // Not reversible — we cannot distinguish which rows were originally
        // 'pending' vs 'unverified' after the fact.
    }
};
