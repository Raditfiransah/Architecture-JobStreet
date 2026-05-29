<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Data Login Admin
        User::factory()->admin()->create([
            'name' => 'Admin System',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);

        // Data Login Perusahaan
        $perusahaan = User::factory()->perusahaan()->create([
            'name' => 'Perusahaan BUMN',
            'email' => 'perusahaan@perusahaan.com',
            'password' => bcrypt('password'),
        ]);
        \App\Models\CompanyProfile::create([
            'user_id' => $perusahaan->id,
            'company_name' => 'Perusahaan BUMN',
        ]);

        // Data Login Arsitek
        $arsitek = User::factory()->arsitek()->create([
            'name' => 'Arsitek Senior',
            'email' => 'arsitek@arsitek.com',
            'password' => bcrypt('password'),
        ]);
        \App\Models\ArsitekProfile::factory()->create([
            'user_id' => $arsitek->id,
        ]);

        $client = User::factory()->client()->create([
            'name' => 'Client Property',
            'email' => 'client@client.com',
            'password' => bcrypt('password'),
        ]);

        \App\Models\ClientProfile::create([
            'user_id' => $client->id,
        ]);
        // Data Dummy Hire Arsitek (tambahan acak)
        User::factory(10)->arsitek()->create()->each(function (User $user) {
            \App\Models\ArsitekProfile::factory()->create([
                'user_id' => $user->id,
            ]);
        });

        // Data Dummy Perusahaan untuk keperluan lowongan kerja (opsional tapi baiknya ada)
        User::factory(3)->perusahaan()->create();

        // Data Dummy Lowongan Kerja
        $this->call([
            LowonganSeeder::class,
        ]);
    }
}