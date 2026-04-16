<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_profiles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('client_type', 50)->nullable(); // Individu, Pengembang, Instansi Pemerintah, Swasta
            $table->string('address', 500)->nullable();
            $table->json('project_interests')->nullable(); // Residential, Commercial, Renovasi, Interior
            $table->string('budget_range', 50)->nullable(); // <50jt, 50-200jt, etc
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_profiles');
    }
};
