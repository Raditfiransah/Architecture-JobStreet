<?php

namespace Database\Seeders;

use App\Models\Lamaran;
use App\Models\Lowongan;
use App\Models\User;
use Illuminate\Database\Seeder;

class LamaranSeeder extends Seeder
{
    public function run(): void
    {
        // Arsitek yang akan melamar lowongan
        $arsitekEmails = [
            'rizky.aditya@example.com',
            'sari.dewi@example.com',
            'bimo.wahyu@example.com',
            'cahya.puspita@example.com',
            'dika.firmansyah@example.com',
            'elisa.mariana@example.com',
            'fajar.eka@example.com',
            'gita.nirmala@example.com',
            'arsitek@arsitek.com',
            'arsitek@example.com',
        ];

        $arsiteks = User::whereIn('email', $arsitekEmails)->pluck('id', 'email');
        $lowongans = Lowongan::all();

        if ($lowongans->isEmpty() || $arsiteks->isEmpty()) {
            return;
        }

        $getArsitekId = fn($email) => $arsiteks[$email] ?? null;

        $lamarans = [
            // Senior Arsitek – Arkindo Studio
            [
                'lowongan_posisi' => 'Senior Arsitek – Proyek Residensial',
                'arsitek_email'   => 'rizky.aditya@example.com',
                'status'          => 'shortlisted',
                'notes'           => 'Kandidat sangat kuat. Portofolio residensial premium sangat relevan. Jadwalkan interview teknikal.',
                'applied_at'      => now()->subDays(10),
            ],
            [
                'lowongan_posisi' => 'Senior Arsitek – Proyek Residensial',
                'arsitek_email'   => 'fajar.eka@example.com',
                'status'          => 'interview',
                'notes'           => 'Pengalaman hospitality sangat bagus. Interview HR sudah selesai, jadwal interview user.',
                'applied_at'      => now()->subDays(12),
            ],
            [
                'lowongan_posisi' => 'Senior Arsitek – Proyek Residensial',
                'arsitek_email'   => 'gita.nirmala@example.com',
                'status'          => 'reviewing',
                'notes'           => null,
                'applied_at'      => now()->subDays(7),
            ],
            // 3D Visualizer – Arkindo Studio
            [
                'lowongan_posisi' => '3D Visualizer – Fotorealistik',
                'arsitek_email'   => 'elisa.mariana@example.com',
                'status'          => 'pending',
                'notes'           => null,
                'applied_at'      => now()->subDays(3),
            ],
            // BIM Manager – PT Graha Design
            [
                'lowongan_posisi' => 'BIM Manager',
                'arsitek_email'   => 'dika.firmansyah@example.com',
                'status'          => 'accepted',
                'notes'           => 'Sertifikasi Autodesk lengkap. Penawaran gaji sudah disetujui. Mulai bulan depan.',
                'applied_at'      => now()->subDays(20),
            ],
            [
                'lowongan_posisi' => 'BIM Manager',
                'arsitek_email'   => 'bimo.wahyu@example.com',
                'status'          => 'rejected',
                'notes'           => 'Pengalaman BIM kurang dari requirement minimum 5 tahun.',
                'applied_at'      => now()->subDays(18),
            ],
            // Interior Designer – Kana Interiors
            [
                'lowongan_posisi' => 'Interior Designer – Spesialis Residensial',
                'arsitek_email'   => 'sari.dewi@example.com',
                'status'          => 'interview',
                'notes'           => 'Portofolio kafe sangat impresif. Tertarik untuk diuji di proyek residensial.',
                'applied_at'      => now()->subDays(8),
            ],
            [
                'lowongan_posisi' => 'Interior Designer – Spesialis Residensial',
                'arsitek_email'   => 'elisa.mariana@example.com',
                'status'          => 'reviewing',
                'notes'           => null,
                'applied_at'      => now()->subDays(4),
            ],
            // Magang – Kana Interiors
            [
                'lowongan_posisi' => 'Magang Desain Interior (3 Bulan)',
                'arsitek_email'   => 'elisa.mariana@example.com',
                'status'          => 'shortlisted',
                'notes'           => 'Fresh graduate ITS, portofolio tugas akhir bagus.',
                'applied_at'      => now()->subDays(2),
            ],
            // Project Manager – PT Aedifica
            [
                'lowongan_posisi' => 'Project Manager – Konstruksi Gedung',
                'arsitek_email'   => 'fajar.eka@example.com',
                'status'          => 'reviewing',
                'notes'           => null,
                'applied_at'      => now()->subDays(6),
            ],
            // Junior Arsitek – Studio 49
            [
                'lowongan_posisi' => 'Junior Arsitek',
                'arsitek_email'   => 'elisa.mariana@example.com',
                'status'          => 'pending',
                'notes'           => null,
                'applied_at'      => now()->subDays(1),
            ],
            [
                'lowongan_posisi' => 'Junior Arsitek',
                'arsitek_email'   => 'bimo.wahyu@example.com',
                'status'          => 'shortlisted',
                'notes'           => 'Background UGM + portofolio sayembara TOD sangat relevan meski beda spesialisasi.',
                'applied_at'      => now()->subDays(3),
            ],
            // Urban Planner – PT Rekayasa Kota
            [
                'lowongan_posisi' => 'Urban Planner – Smart City Specialist',
                'arsitek_email'   => 'bimo.wahyu@example.com',
                'status'          => 'interview',
                'notes'           => 'Kandidat sangat cocok. Pengalaman TOD dan smartcity langsung relevan.',
                'applied_at'      => now()->subDays(14),
            ],
            [
                'lowongan_posisi' => 'Urban Planner – Smart City Specialist',
                'arsitek_email'   => 'gita.nirmala@example.com',
                'status'          => 'rejected',
                'notes'           => 'Spesialisasi fasilitas publik, bukan urban planning. Kurang sesuai requirement.',
                'applied_at'      => now()->subDays(11),
            ],
            // Arsitek from DatabaseSeeder applying too
            [
                'lowongan_posisi' => 'Senior Arsitek – Proyek Residensial',
                'arsitek_email'   => 'arsitek@arsitek.com',
                'status'          => 'pending',
                'notes'           => null,
                'applied_at'      => now()->subDays(5),
            ],
            [
                'lowongan_posisi' => 'Junior Arsitek',
                'arsitek_email'   => 'arsitek@example.com',
                'status'          => 'pending',
                'notes'           => null,
                'applied_at'      => now()->subDays(2),
            ],
        ];

        foreach ($lamarans as $data) {
            $arsitekId = $getArsitekId($data['arsitek_email']);
            $lowongan  = $lowongans->where('posisi', $data['lowongan_posisi'])->first();

            if (! $arsitekId || ! $lowongan) {
                continue;
            }

            Lamaran::updateOrCreate(
                [
                    'user_id'     => $arsitekId,
                    'lowongan_id' => $lowongan->id,
                ],
                [
                    'status'     => $data['status'],
                    'notes'      => $data['notes'],
                    'applied_at' => $data['applied_at'],
                ]
            );
        }
    }
}
