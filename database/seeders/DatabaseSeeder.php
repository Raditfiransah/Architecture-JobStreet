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
            'name' => 'Admin JobStreet',
            'email' => 'admin@jobstreet.com',
            'password' => bcrypt('password'),
        ]);

        // Data Dummy Hire Arsitek
        User::factory(10)->arsitek()->create()->each(function (User $user) {
            \App\Models\ArsitekProfile::factory()->create([
                'user_id' => $user->id,
            ]);
        });

        // Data Dummy Perusahaan untuk keperluan lowongan kerja (opsional tapi baiknya ada)
        User::factory(3)->perusahaan()->create();

        // Data Dummy Lowongan Kerja (Sudah ada di LowonganSeeder)
        $this->call([
            UserSeeder::class,
            LowonganSeeder::class,
        ]);
    }
}
