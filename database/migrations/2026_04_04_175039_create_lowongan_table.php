<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lowongan', function (Blueprint $table) {
            $table->id();
            $table->string('posisi');
            $table->string('perusahaan');
            $table->string('kota');
            $table->enum('tipe', ['Full Time', 'Part Time', 'Freelance', 'Contract', 'Internship'])->default('Full Time');
            $table->string('gaji')->nullable();
            $table->string('inisial', 5);
            $table->decimal('rating', 2, 1)->default(0.0);
            $table->text('deskripsi');
            $table->json('syarat');
            $table->json('tanggung_jawab');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lowongan');
    }
};
