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
        Schema::create('lamarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lowongan_id')->constrained('lowongan')->onDelete('cascade');
            $table->enum('status', ['pending', 'reviewing', 'shortlisted', 'interview', 'rejected', 'accepted'])->default('pending');
            $table->string('cv_path')->nullable();
            $table->string('portfolio_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lamarans');
    }
};
