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
        Schema::table('users', function (Blueprint $table) {
            $table->string('location')->nullable()->after('avatar_url');
            $table->string('phone')->nullable()->after('location');
        });

        Schema::table('company_profiles', function (Blueprint $table) {
            $table->string('industry')->nullable()->after('company_name');
            $table->string('company_size')->nullable()->after('industry');
            $table->string('location')->nullable()->after('company_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['location', 'phone']);
        });

        Schema::table('company_profiles', function (Blueprint $table) {
            $table->dropColumn(['industry', 'company_size', 'location']);
        });
    }
};
