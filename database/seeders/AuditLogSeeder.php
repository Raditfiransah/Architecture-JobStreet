<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuditLogSeeder extends Seeder
{
    public function run(): void
    {
        $admins   = User::where('role', 'admin')->get();
        $arsiteks = User::where('role', 'arsitek')->get();
        $clients  = User::where('role', 'client')->get();
        $perus    = User::where('role', 'perusahaan')->get();

        if ($admins->isEmpty()) {
            return;
        }

        $adminId  = $admins->first()->id;
        $adminId2 = $admins->count() > 1 ? $admins->skip(1)->first()->id : $adminId;

        $logs = [];

        // ── Admin actions ─────────────────────────────────────────────────────
        foreach ($arsiteks->take(5) as $arsitek) {
            $logs[] = [
                'admin_id'   => $adminId,
                'user_id'    => $arsitek->id,
                'action'     => 'verify_arsitek',
                'details'    => "Verifikasi profil arsitek #{$arsitek->id} ({$arsitek->email}) disetujui. Lisensi IAI valid.",
                'ip_address' => '192.168.1.10',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subDays(rand(1, 60)),
                'updated_at' => now()->subDays(rand(1, 60)),
            ];
        }

        foreach ($arsiteks->skip(5)->take(2) as $arsitek) {
            $logs[] = [
                'admin_id'   => $adminId2,
                'user_id'    => $arsitek->id,
                'action'     => 'reject_arsitek_verification',
                'details'    => "Verifikasi profil arsitek #{$arsitek->id} ditolak. Alasan: Dokumen lisensi IAI tidak terbaca / expired.",
                'ip_address' => '192.168.1.11',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15',
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(1, 30)),
            ];
        }

        foreach ($clients->take(3) as $client) {
            $logs[] = [
                'admin_id'   => $adminId,
                'user_id'    => $client->id,
                'action'     => 'verify_client',
                'details'    => "Verifikasi akun client #{$client->id} ({$client->email}) berhasil. Dokumen kepemilikan lahan valid.",
                'ip_address' => '10.0.0.5',
                'user_agent' => 'Mozilla/5.0 (Linux; Android 12; Pixel 6)',
                'created_at' => now()->subDays(rand(5, 90)),
                'updated_at' => now()->subDays(rand(5, 90)),
            ];
        }

        foreach ($perus->take(3) as $peru) {
            $logs[] = [
                'admin_id'   => $adminId,
                'user_id'    => $peru->id,
                'action'     => 'verify_company',
                'details'    => "Verifikasi perusahaan #{$peru->id} ({$peru->email}) disetujui. NIB dan NPWP sudah tervalidasi.",
                'ip_address' => '10.0.0.7',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'created_at' => now()->subDays(rand(3, 45)),
                'updated_at' => now()->subDays(rand(3, 45)),
            ];
        }

        // ── User login / activity ─────────────────────────────────────────────
        $allUsers = User::all()->take(15);
        foreach ($allUsers as $user) {
            $logs[] = [
                'admin_id'   => null,
                'user_id'    => $user->id,
                'action'     => 'user_login',
                'details'    => "User {$user->email} berhasil login. Role: {$user->role}.",
                'ip_address' => '103.20.' . rand(1, 254) . '.' . rand(1, 254),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/124.0',
                'created_at' => now()->subDays(rand(0, 7)),
                'updated_at' => now()->subDays(rand(0, 7)),
            ];
        }

        // ── Admin info hub actions ──────────────────────────────────────────
        $logs[] = [
            'admin_id'   => $adminId,
            'user_id'    => null,
            'action'     => 'create_info_hub',
            'details'    => 'Admin membuat postingan InfoHub baru: "Kongres Arsitek Indonesia 2026".',
            'ip_address' => '192.168.1.10',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'created_at' => now()->subDays(14),
            'updated_at' => now()->subDays(14),
        ];
        $logs[] = [
            'admin_id'   => $adminId2,
            'user_id'    => null,
            'action'     => 'create_info_hub',
            'details'    => 'Admin membuat postingan InfoHub baru: "Sayembara Desain Monumen Kemerdekaan 2045".',
            'ip_address' => '192.168.1.11',
            'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X)',
            'created_at' => now()->subDays(30),
            'updated_at' => now()->subDays(30),
        ];

        // ── Password reset & profile update ──────────────────────────────────
        if ($arsiteks->count() >= 2) {
            $arsitek2 = $arsiteks->skip(1)->first();
            $logs[] = [
                'admin_id'   => null,
                'user_id'    => $arsitek2->id,
                'action'     => 'profile_update',
                'details'    => "User {$arsitek2->email} memperbarui profil: software_skills, bio, dan external_portfolio_url diperbarui.",
                'ip_address' => '180.245.' . rand(1, 254) . '.' . rand(1, 254),
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0)',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ];
        }

        if ($clients->count() >= 1) {
            $client1 = $clients->first();
            $logs[] = [
                'admin_id'   => null,
                'user_id'    => $client1->id,
                'action'     => 'proyek_posted',
                'details'    => "Client {$client1->email} memposting proyek baru: \"Desain Rumah Tinggal Mewah Pondok Indah\".",
                'ip_address' => '114.4.' . rand(1, 254) . '.' . rand(1, 254),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0)',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ];
        }

        DB::table('audit_logs')->insert($logs);
    }
}
