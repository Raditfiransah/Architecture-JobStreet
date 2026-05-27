<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ClientProfile;
use App\Models\ArsitekProfile;
use App\Models\Proyek;
use App\Models\Proposal;
use Illuminate\Support\Facades\Hash;

class ProyekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create a Default Client
        $clientUser = User::create([
            'name' => 'Budi Wijaya (Client)',
            'email' => 'client@example.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'is_active' => true,
            'is_verified' => true,
            'location' => 'Malang, Jawa Timur',
            'phone' => '08123456789',
            'email_verified_at' => now(),
        ]);

        ClientProfile::create([
            'user_id' => $clientUser->id,
            'client_type' => 'Perorangan',
            'address' => 'Jl. Ijen No. 12, Malang',
            'project_interests' => ['Residensial', 'Komersial', 'Desain Interior'],
            'budget_range' => 'Rp 10.000.000 - Rp 100.000.000',
        ]);

        // 2. Create a Default Architect
        $arsitekUser = User::create([
            'name' => 'Ahmad Dani (Arsitek)',
            'email' => 'arsitek@example.com',
            'password' => Hash::make('password'),
            'role' => 'arsitek',
            'is_active' => true,
            'is_verified' => true,
            'location' => 'Surabaya, Jawa Timur',
            'phone' => '08223456789',
            'email_verified_at' => now(),
        ]);

        ArsitekProfile::create([
            'user_id' => $arsitekUser->id,
            'status_pekerjaan' => 'Arsitek Profesional (IAI)',
            'education_institution' => 'Institut Teknologi Sepuluh Nopember',
            'software_skills' => ['AutoCAD', 'SketchUp', 'Revit', '3ds Max', 'Lumion'],
            'location' => 'Surabaya',
            'bio' => 'Arsitek berpengalaman merancang bangunan residensial modern kontemporer dan vila mewah ramah lingkungan.',
            'verification_status' => 'verified',
        ]);

        // 3. Create Sample Projects posted by the Client
        $proyek1 = Proyek::create([
            'user_id' => $clientUser->id,
            'title' => 'Desain Vila Modern Tropis Bali',
            'category' => 'Residensial (Rumah, Villa, Apartemen)',
            'location' => 'Canggu, Bali',
            'budget' => 45000000,
            'description' => "Saya mencari arsitek berbakat untuk mendesain vila mewah modern tropis 2 lantai di Canggu, Bali.\n\nKebutuhan Spesifikasi:\n- Luas Tanah: 300 m2\n- Kamar Tidur: 3 (semua kamar mandi dalam)\n- Kolam Renang: Ukuran minimal 3x8 meter\n- Konsep: Terbuka (open-plan) dengan ventilasi silang alami dan material kayu lokal premium.\n\nOutput yang diharapkan adalah denah lengkap, render 3D eksterior & interior, serta RAB awal pengerjaan.",
            'status' => 'aktif',
        ]);

        $proyek2 = Proyek::create([
            'user_id' => $clientUser->id,
            'title' => 'Desain Interior Kafe Minimalis Malang',
            'category' => 'Desain Interior',
            'location' => 'Klojen, Malang',
            'budget' => 15000000,
            'description' => "Dibutuhkan jasa desain interior untuk renovasi ruko 2 lantai menjadi kafe kopi minimalis bernuansa jepang (Japandi).\n\nKebutuhan:\n- Lantai 1: Area barista, kasir, dan tempat duduk outdoor/indoor semi-terbuka.\n- Lantai 2: Area duduk ber-AC (co-working space friendly) dengan pencahayaan hangat.\n- Target pasar: Mahasiswa dan pekerja WFH.\n\nKirimkan proposal penawaran beserta portofolio kafe sejenis yang pernah Anda kerjakan.",
            'status' => 'aktif',
        ]);

        $proyek3 = Proyek::create([
            'user_id' => $clientUser->id,
            'title' => 'Boutique Hotel & Resort Lombok',
            'category' => 'Komersial (Ruko, Kantor, Hotel, Kafe)',
            'location' => 'Senggigi, Lombok',
            'budget' => 120000000,
            'description' => "Biro kami berniat membangun butik hotel ramah lingkungan dengan konsep resort bambu di Senggigi, Lombok.\n\nSpesifikasi:\n- Area: 1 Hektar tepi pantai\n- Unit: 15 cottage bambu eksklusif, lobby utama, restoran terapung, dan area spa.\n- Konsep: Menggunakan arsitektur bambu melengkung (curved bamboo architecture) dengan nol emisi karbon.\n\nArsitek yang terpilih diharapkan bersedia melakukan survey lokasi bersama tim konstruksi kami.",
            'status' => 'aktif',
        ]);

        // 4. Create an initial sample proposal from the architect to Proyek 1
        Proposal::create([
            'user_id' => $arsitekUser->id,
            'proyek_id' => $proyek1->id,
            'bid_amount' => 40000000,
            'estimated_time' => 30,
            'description' => "Halo Bapak Budi,\n\nPerkenalkan saya Ahmad Dani, arsitek profesional asal Surabaya. Saya sangat tertarik dengan proyek Vila Modern Tropis Bali Anda karena sangat sejalan dengan keahlian biro desain saya.\n\nKonsep Penawaran saya:\n- Konsep Green Villa: Mengintegrasikan sirkulasi udara optimal dan bayangan vegetasi alami untuk meredam hawa panas Canggu tanpa boros AC.\n- Material Lokal: Menggunakan kombinasi batu alam paras Jogja dan kayu ulin bekas kapal yang tahan cuaca.\n- Kelengkapan Output: Saya akan memberikan Gambar DED Arsitektur lengkap, 5 View Render 3D photorealistic, dan estimasi RAB struktur.\n\nSaya lampirkan dokumen konsep serupa yang baru saja selesai kami kerjakan di Uluwatu untuk referensi Anda. Mari berkonsultasi lebih lanjut!",
            'status' => 'pending',
        ]);
    }
}
