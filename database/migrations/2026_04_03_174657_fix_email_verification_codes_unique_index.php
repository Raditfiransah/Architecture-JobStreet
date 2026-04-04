<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_verification_codes', function (Blueprint $table) {
            // Drop the global unique on code — two different users can have the same OTP
            $table->dropUnique(['code']);

            // Add composite unique: same user can't have duplicate active codes
            $table->unique(['user_id', 'code']);

            // Add index for faster lookup by user + used status
            $table->index(['user_id', 'is_used', 'expired_at']);
        });
    }

    public function down(): void
    {
        Schema::table('email_verification_codes', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'is_used', 'expired_at']);
            $table->dropUnique(['user_id', 'code']);
            $table->unique('code');
        });
    }
};
