<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Data Login Admin
        User::updateOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'name' => 'Admin System',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'is_active' => true,
            'is_verified' => true,
        ]);

        // Data Login Perusahaan
        $perusahaan = User::updateOrCreate([
            'email' => 'perusahaan@perusahaan.com',
        ], [
            'name' => 'Perusahaan BUMN',
            'password' => Hash::make('password'),
            'role' => 'perusahaan',
            'email_verified_at' => now(),
            'is_active' => true,
            'is_verified' => true,
        ]);
        \App\Models\CompanyProfile::updateOrCreate([
            'user_id' => $perusahaan->id,
        ], [
            'company_name' => 'Perusahaan BUMN',
        ]);

        // Data Login Arsitek
        $arsitek = User::updateOrCreate([
            'email' => 'arsitek@arsitek.com',
        ], [
            'name' => 'Arsitek Senior',
            'password' => Hash::make('password'),
            'role' => 'arsitek',
            'email_verified_at' => now(),
            'is_active' => true,
            'is_verified' => true,
        ]);
        \App\Models\ArsitekProfile::updateOrCreate([
            'user_id' => $arsitek->id,
        ], [
            'first_name' => 'Arsitek',
            'last_name' => 'Senior',
            'status_pekerjaan' => 'Available',
        ]);

        $client = User::updateOrCreate([
            'email' => 'client@client.com',
        ], [
            'name' => 'Client Property',
            'password' => Hash::make('password'),
            'role' => 'client',
            'email_verified_at' => now(),
            'is_active' => true,
            'is_verified' => true,
        ]);

        \App\Models\ClientProfile::updateOrCreate([
            'user_id' => $client->id,
        ], [
            'client_type' => 'Individu',
        ]);
        // Data Dummy Hire Arsitek (tambahan acak)
        User::factory(10)->arsitek()->create()->each(function (User $user) {
            \App\Models\ArsitekProfile::factory()->create([
                'user_id' => $user->id,
            ]);
        });

        // Data Dummy Perusahaan untuk keperluan lowongan kerja (opsional tapi baiknya ada)
        User::factory(3)->perusahaan()->create();

        // Data Dummy Lowongan Kerja (dari seeder lama, tetap dipertahankan)
        $this->call([
            UserSeeder::class,
            LowonganSeeder::class,
            ProyekSeeder::class,
            InfoHubSeeder::class,
        ]);

        // ── Data Dummy Lengkap ────────────────────────────────────────────────
        // Arsitek dengan profil detail, portofolio, keahlian, verifikasi
        $this->call(ArsitekProfileSeeder::class);

        // Perusahaan dengan profil detail + lowongan kerja baru
        $this->call(PerusahaanSeeder::class);

        // Client dengan profil + proyek yang diposting
        $this->call(ClientSeeder::class);

        // Proposal: arsitek melamar ke proyek
        $this->call(ProposalSeeder::class);

        // Lamaran: arsitek melamar ke lowongan
        $this->call(LamaranSeeder::class);

        // InfoHub tambahan: event, sayembara, magang lebih banyak
        $this->call(InfoHubExtraSeeder::class);

        // Audit log aktivitas admin dan user
        $this->call(AuditLogSeeder::class);
    }
}
