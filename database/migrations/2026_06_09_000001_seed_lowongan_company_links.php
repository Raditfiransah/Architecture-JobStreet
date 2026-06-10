<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Migration ini bukan DDL — ia memperbaiki data:
 * 1. Upsert user + company_profile untuk setiap perusahaan di tabel lowongan
 * 2. Set lowongan.user_id ke user yang tepat berdasarkan exact name match
 *
 * Aman dijalankan berkali-kali (idempotent via updateOrInsert).
 */
return new class extends Migration
{
    /**
     * Map: lowongan.perusahaan (exact) → data user & profil
     */
    private function companies(): array
    {
        return [
            'Arkindo Studio' => [
                'email'    => 'hr@arkindo.studio',
                'name'     => 'Arkindo Studio',
                'location' => 'Jakarta Selatan',
                'profile'  => [
                    'company_name'        => 'PT Arkindo Studio',
                    'company_desc'        => 'Arkindo Studio adalah biro arsitektur terkemuka yang berfokus pada desain residensial mewah, hotel bintang lima, dan kawasan komersial premium di seluruh Indonesia.',
                    'industry'            => 'Jasa Arsitektur & Desain',
                    'company_size'        => '51-200 Karyawan',
                    'business_fields'     => json_encode(['Arsitektur Residensial', 'Hospitality', 'Komersial', 'Interior Design']),
                    'founded_year'        => 2010,
                    'company_website'     => 'https://www.arkindo.studio',
                    'location'            => 'Jl. Kemang Raya No. 21, Jakarta Selatan',
                    'verification_status' => 'verified',
                    'verified_at'         => now(),
                ],
            ],
            'PT Graha Design' => [
                'email'    => 'rekrutmen@grahadesign.id',
                'name'     => 'PT Graha Design',
                'location' => 'Surabaya',
                'profile'  => [
                    'company_name'        => 'PT Graha Design Indonesia',
                    'company_desc'        => 'PT Graha Design adalah firma arsitektur dan engineering terintegrasi dengan pengalaman lebih dari 25 tahun di proyek infrastruktur skala nasional dan gedung pemerintahan.',
                    'industry'            => 'Konstruksi & Rekayasa',
                    'company_size'        => '201-500 Karyawan',
                    'business_fields'     => json_encode(['Engineering', 'Infrastruktur', 'BIM Management', 'Konstruksi']),
                    'founded_year'        => 1999,
                    'company_website'     => 'https://www.grahadesign.id',
                    'location'            => 'Jl. Raya Darmo No. 88, Surabaya',
                    'verification_status' => 'verified',
                    'verified_at'         => now(),
                ],
            ],
            'Kana Interiors' => [
                'email'    => 'jobs@kanainteriors.co',
                'name'     => 'Kana Interiors',
                'location' => 'Bandung',
                'profile'  => [
                    'company_name'        => 'Kana Interiors & Co',
                    'company_desc'        => 'Kana Interiors adalah studio desain interior boutique yang mengkhususkan diri pada proyek residensial premium dan komersial di Bandung dan Jakarta.',
                    'industry'            => 'Desain Interior',
                    'company_size'        => '11-50 Karyawan',
                    'business_fields'     => json_encode(['Interior Residensial', 'Interior Komersial', 'FF&E']),
                    'founded_year'        => 2015,
                    'company_website'     => 'https://www.kanainteriors.co',
                    'location'            => 'Jl. Setiabudi No. 7, Bandung',
                    'verification_status' => 'verified',
                    'verified_at'         => now(),
                ],
            ],
            'PT Rekayasa Kota Nusantara' => [
                'email'    => 'sdm@rekayasanusantara.co.id',
                'name'     => 'PT Rekayasa Kota Nusantara',
                'location' => 'Jakarta Pusat',
                'profile'  => [
                    'company_name'        => 'PT Rekayasa Kota Nusantara',
                    'company_desc'        => 'Konsultan perencanaan kota dengan rekam jejak panjang dalam penyusunan RTRW, RDTR, dan masterplan kawasan strategis nasional termasuk kawasan IKN.',
                    'industry'            => 'Perencanaan Wilayah & Kota',
                    'company_size'        => '51-200 Karyawan',
                    'business_fields'     => json_encode(['Perencanaan Kota', 'Tata Ruang', 'Smart City', 'GIS']),
                    'founded_year'        => 2001,
                    'company_website'     => 'https://www.rekayasanusantara.co.id',
                    'location'            => 'Jl. Thamrin No. 15, Jakarta Pusat',
                    'verification_status' => 'verified',
                    'verified_at'         => now(),
                ],
            ],
            'Taman Hijau Indonesia' => [
                'email'    => 'info@tamanhijau.id',
                'name'     => 'Taman Hijau Indonesia',
                'location' => 'Bali',
                'profile'  => [
                    'company_name'        => 'Taman Hijau Indonesia',
                    'company_desc'        => 'Firma landscape architecture terkemuka yang berfokus pada perancangan taman resort mewah dan ruang publik di seluruh Indonesia.',
                    'industry'            => 'Arsitektur Lanskap & Lingkungan',
                    'company_size'        => '11-50 Karyawan',
                    'business_fields'     => json_encode(['Landscape Architecture', 'Taman Resort', 'Ruang Publik']),
                    'founded_year'        => 2012,
                    'company_website'     => null,
                    'location'            => 'Jl. Sunset Road No. 10, Kuta, Bali',
                    'verification_status' => 'verified',
                    'verified_at'         => now(),
                ],
            ],
            'Studio Empat Sembilan' => [
                'email'    => 'open@studio49.id',
                'name'     => 'Studio Empat Sembilan',
                'location' => 'Yogyakarta',
                'profile'  => [
                    'company_name'        => 'CV Studio Empat Sembilan',
                    'company_desc'        => 'Studio arsitektur independen berbasis di Yogyakarta yang berfokus pada desain residensial kontemporer dengan pendekatan lokalitas dan eksplorasi material nusantara.',
                    'industry'            => 'Arsitektur & Desain',
                    'company_size'        => '1-10 Karyawan',
                    'business_fields'     => json_encode(['Arsitektur Residensial', 'Desain Interior', 'Riset Material']),
                    'founded_year'        => 2018,
                    'company_website'     => 'https://www.studio49.id',
                    'location'            => 'Jl. Kaliurang KM 7, Yogyakarta',
                    'verification_status' => 'verified',
                    'verified_at'         => now(),
                ],
            ],
            'CV Bangun Persada' => [
                'email'    => 'info@bangunpersada.co.id',
                'name'     => 'CV Bangun Persada',
                'location' => 'Malang',
                'profile'  => [
                    'company_name'        => 'CV Bangun Persada',
                    'company_desc'        => 'Kontraktor dan konsultan arsitektur yang melayani proyek residensial dan komersial di wilayah Malang Raya. Mengutamakan kualitas gambar kerja dan ketepatan waktu.',
                    'industry'            => 'Konstruksi & Konsultan',
                    'company_size'        => '11-50 Karyawan',
                    'business_fields'     => json_encode(['Konstruksi Residensial', 'Gambar Kerja', 'RAB & Tender']),
                    'founded_year'        => 2009,
                    'company_website'     => null,
                    'location'            => 'Jl. Soekarno Hatta No. 5, Malang',
                    'verification_status' => 'verified',
                    'verified_at'         => now(),
                ],
            ],
            'PT Infrastruktor Muda' => [
                'email'    => 'hrd@infrastruktormuda.id',
                'name'     => 'PT Infrastruktor Muda',
                'location' => 'Surabaya',
                'profile'  => [
                    'company_name'        => 'PT Infrastruktor Muda',
                    'company_desc'        => 'Konsultan MEP berpengalaman menangani koordinasi sistem mekanikal, elektrikal, dan plumbing untuk gedung bertingkat tinggi dan infrastruktur publik.',
                    'industry'            => 'Engineering MEP',
                    'company_size'        => '51-200 Karyawan',
                    'business_fields'     => json_encode(['MEP Engineering', 'Koordinasi Sistem Bangunan', 'Infrastruktur']),
                    'founded_year'        => 2011,
                    'company_website'     => null,
                    'location'            => 'Jl. Ahmad Yani No. 20, Surabaya',
                    'verification_status' => 'verified',
                    'verified_at'         => now(),
                ],
            ],
            'Aedifica Nusantara' => [
                'email'    => 'karir@aedifica.co.id',
                'name'     => 'Aedifica Nusantara',
                'location' => 'Jakarta Barat',
                'profile'  => [
                    'company_name'        => 'PT Aedifica Nusantara',
                    'company_desc'        => 'Perusahaan manajemen konstruksi dengan track record lebih dari 100 proyek besar. Mengelola proyek dari perencanaan hingga commissioning dengan standar internasional.',
                    'industry'            => 'Manajemen Proyek & Konstruksi',
                    'company_size'        => '501-1000 Karyawan',
                    'business_fields'     => json_encode(['Project Management', 'Construction Management', 'Quantity Surveying']),
                    'founded_year'        => 2005,
                    'company_website'     => 'https://www.aedifica.co.id',
                    'location'            => 'Jl. Mangga Dua Raya No. 5, Jakarta Barat',
                    'verification_status' => 'verified',
                    'verified_at'         => now(),
                ],
            ],
            'Render Studio ID' => [
                'email'    => 'hello@renderstudio.id',
                'name'     => 'Render Studio ID',
                'location' => 'Bandung',
                'profile'  => [
                    'company_name'        => 'Render Studio ID',
                    'company_desc'        => 'Studio visualisasi arsitektur profesional yang menghasilkan rendering fotorealistik dan animasi walkthrough berkualitas tinggi untuk biro arsitektur dan developer properti.',
                    'industry'            => 'Visualisasi & CGI',
                    'company_size'        => '1-10 Karyawan',
                    'business_fields'     => json_encode(['3D Rendering', 'Animasi Arsitektur', 'CGI & Visualisasi']),
                    'founded_year'        => 2020,
                    'company_website'     => null,
                    'location'            => 'Jl. Dago No. 55, Bandung',
                    'verification_status' => 'verified',
                    'verified_at'         => now(),
                ],
            ],
        ];
    }

    public function up(): void
    {
        $now = now();

        foreach ($this->companies() as $lowonganName => $data) {
            // 1. Upsert user perusahaan
            DB::table('users')->updateOrInsert(
                ['email' => $data['email']],
                [
                    'name'              => $data['name'],
                    'password'          => Hash::make('password'),
                    'role'              => 'perusahaan',
                    'is_active'         => true,
                    'is_verified'       => true,
                    'email_verified_at' => $now,
                    'location'          => $data['location'],
                    'updated_at'        => $now,
                    'created_at'        => $now,
                ]
            );

            $userId = DB::table('users')->where('email', $data['email'])->value('id');

            // 2. Upsert company_profile
            DB::table('company_profiles')->updateOrInsert(
                ['user_id' => $userId],
                array_merge($data['profile'], [
                    'user_id'    => $userId,
                    'updated_at' => $now,
                    'created_at' => $now,
                ])
            );

            // 3. Update lowongan.user_id by exact perusahaan name
            DB::table('lowongan')
                ->where('perusahaan', $lowonganName)
                ->whereNull('user_id')   // only fix unlinked ones
                ->update(['user_id' => $userId]);
        }
    }

    public function down(): void
    {
        // Revert: set user_id back to null for these lowongan
        $names = array_keys($this->companies());
        DB::table('lowongan')->whereIn('perusahaan', $names)->update(['user_id' => null]);

        // Remove the company users created by this migration
        $emails = array_column($this->companies(), 'email');
        $userIds = DB::table('users')->whereIn('email', $emails)->pluck('id');
        DB::table('company_profiles')->whereIn('user_id', $userIds)->delete();
        DB::table('users')->whereIn('email', $emails)->delete();
    }
};
