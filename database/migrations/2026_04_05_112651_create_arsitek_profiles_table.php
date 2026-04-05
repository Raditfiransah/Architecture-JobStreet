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
        Schema::create('arsitek_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('status_pekerjaan', 100)->nullable();
            $table->boolean('is_student')->default(false);
            $table->string('location', 200)->nullable();
            $table->string('school', 200)->nullable();
            $table->string('degree_type', 100)->nullable();
            $table->json('preferences')->nullable();
            $table->string('resume_url', 500)->nullable();
            $table->string('portfolio_url', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsitek_profiles');
    }
};
