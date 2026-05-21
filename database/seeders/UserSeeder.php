<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Account
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'is_verified' => true,
        ]);

        User::create([
            'name' => 'Administrator1',
            'email' => 'sharren@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'is_verified' => true,
        ]);

        // Arsitek Account
        $arsitek = User::create([
            'name' => 'Arsitek Profesional',
            'email' => 'arsitek@example.com',
            'password' => Hash::make('password'),
            'role' => 'arsitek',
            'is_active' => true,
            'is_verified' => true,
        ]);
        
        DB::table('arsitek_profiles')->insert([
            'user_id' => $arsitek->id,
            'first_name' => 'Arsitek',
            'last_name' => 'Profesional',
            'status_pekerjaan' => 'Freelance',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Perusahaan Account
        $perusahaan = User::create([
            'name' => 'Perusahaan Konstruksi',
            'email' => 'perusahaan@example.com',
            'password' => Hash::make('password'),
            'role' => 'perusahaan',
            'is_active' => true,
            'is_verified' => true,
        ]);

        DB::table('company_profiles')->insert([
            'user_id' => $perusahaan->id,
            'company_name' => 'PT Konstruksi Jaya',
            'company_desc' => 'Perusahaan yang bergerak di bidang konstruksi dan arsitektur.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
