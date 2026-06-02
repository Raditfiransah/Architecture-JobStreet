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
        User::updateOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Administrator',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        User::updateOrCreate([
            'email' => 'nino@example.com',
        ], [
            'name' => 'Administrator1',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        // Arsitek Account
        $arsitek = User::updateOrCreate([
            'email' => 'arsitek@example.com',
        ], [
            'name' => 'Arsitek Profesional',
            'password' => Hash::make('password'),
            'role' => 'arsitek',
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);
        
        DB::table('arsitek_profiles')->updateOrInsert([
            'user_id' => $arsitek->id,
        ], [
            'first_name' => 'Arsitek',
            'last_name' => 'Profesional',
            'status_pekerjaan' => 'Freelance',
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        // Perusahaan Account
        $perusahaan = User::updateOrCreate([
            'email' => 'perusahaan@example.com',
        ], [
            'name' => 'Perusahaan Konstruksi',
            'password' => Hash::make('password'),
            'role' => 'perusahaan',
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        DB::table('company_profiles')->updateOrInsert([
            'user_id' => $perusahaan->id,
        ], [
            'company_name' => 'PT Konstruksi Jaya',
            'company_desc' => 'Perusahaan yang bergerak di bidang konstruksi dan arsitektur.',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
    }
}
