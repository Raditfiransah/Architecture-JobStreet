<?php

namespace Database\Seeders;

use App\Models\CompanyProfile;
use App\Models\Lowongan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Fix lowongan yang user_id-nya null atau salah (mengarah ke perusahaan yang
 * tidak sesuai dengan kolom `perusahaan`).
 *
 * Strategi:
 * 1. Reset user_id ke null untuk semua lowongan yang user_id-nya tidak cocok
 *    dengan nama perusahaannya.
 * 2. Untuk setiap lowongan unik berdasarkan nama perusahaan, cari atau buat
 *    user perusahaan yang tepat lalu set user_id.
 */
class FixLowonganUserIdSeeder extends Seeder
{
    // Peta nama perusahaan di kolom `lowongan.perusahaan` → data profil lengkap
    private array $companyMap = [
        'Arkindo Studio' => [
            'email'        => 'hr@arkindo.studio',
            'location'     => 'Jakarta Selatan',
            'profile'      => [
                'company_name'        => 'PT Arkindo Studio',
                'company_desc'        => 'Arkindo Studio adalah biro arsitektur terkemuka yang berfokus pada desain residensial mewah, hotel bintang lima, dan kawasan komersial premium.',
                'industry'            => 'Jasa Arsitektur & Desain',
                'company_size'        => '51-200 Karyawan',
                'business_fields'     => ['Arsitektur Residensial', 'Hospitality', 'Komersial', 'Interior Design'],
                'founded_year'        => 2010,
                'company_website'     => 'https://www.arkindo.studio',
                'verification_status' => 'verified',
            ],
        ],
        'PT Graha Design' => [
            'email'        => 'rekrutmen@grahadesign.id',
            'location'     => 'Surabaya',
            'profile'      => [
                'company_name'        => 'PT Graha Design Indonesia',
                'company_desc'        => 'PT Graha Design adalah firma arsitektur dan engineering terintegrasi dengan pengalaman lebih dari 25 tahun di proyek infrastruktur dan gedung pemerintahan.',
                'industry'            => 'Konstruksi & Rekayasa',
                'company_size'        => '201-500 Karyawan',
                'business_fields'     => ['Engineering', 'Infrastruktur', 'BIM Management', 'Konstruksi'],
                'founded_year'        => 1999,
                'company_website'     => 'https://www.grahadesign.id',
                'verification_status' => 'verified',
            ],
        ],
        'Kana Interiors' => [
            'email'        => 'jobs@kanainteriors.co',
            'location'     => 'Bandung',
            'profile'      => [
                'company_name'        => 'Kana Interiors & Co',
                'company_desc'        => 'Kana Interiors adalah studio desain interior boutique yang mengkhususkan diri pada proyek residensial premium dan komersial.',
                'industry'            => 'Desain Interior',
                'company_size'        => '11-50 Karyawan',
                'business_fields'     => ['Interior Residensial', 'Interior Komersial', 'FF&E'],
                'founded_year'        => 2015,
                'company_website'     => 'https://www.kanainteriors.co',
                'verification_status' => 'verified',
            ],
        ],
        'PT Rekayasa Kota Nusantara' => [
            'email'        => 'sdm@rekayasanusantara.co.id',
            'location'     => 'Jakarta Pusat',
            'profile'      => [
                'company_name'        => 'PT Rekayasa Kota Nusantara',
                'company_desc'        => 'Konsultan perencanaan kota dengan rekam jejak panjang dalam penyusunan RTRW, RDTR, masterplan kawasan strategis nasional termasuk IKN.',
                'industry'            => 'Perencanaan Wilayah & Kota',
                'company_size'        => '51-200 Karyawan',
                'business_fields'     => ['Perencanaan Kota', 'Tata Ruang', 'Smart City', 'GIS'],
                'founded_year'        => 2001,
                'company_website'     => 'https://www.rekayasanusantara.co.id',
                'verification_status' => 'verified',
            ],
        ],
        'Taman Hijau Indonesia' => [
            'email'        => 'info@tamanhijau.id',
            'location'     => 'Bali',
            'profile'      => [
                'company_name'        => 'Taman Hijau Indonesia',
                'company_desc'        => 'Firma landscape architecture terkemuka yang berfokus pada perancangan taman resort mewah, ruang publik, dan lanskap kawasan perumahan premium.',
                'industry'            => 'Arsitektur Lanskap & Lingkungan',
                'company_size'        => '11-50 Karyawan',
                'business_fields'     => ['Landscape Architecture', 'Taman Resort', 'Ruang Publik'],
                'founded_year'        => 2012,
                'company_website'     => null,
                'verification_status' => 'verified',
            ],
        ],
        'Studio Empat Sembilan' => [
            'email'        => 'open@studio49.id',
            'location'     => 'Yogyakarta',
            'profile'      => [
                'company_name'        => 'CV Studio Empat Sembilan',
                'company_desc'        => 'Studio arsitektur independen berbasis di Yogyakarta yang berfokus pada desain residensial kontemporer dengan pendekatan lokalitas dan material nusantara.',
                'industry'            => 'Arsitektur & Desain',
                'company_size'        => '1-10 Karyawan',
                'business_fields'     => ['Arsitektur Residensial', 'Desain Interior', 'Riset Material'],
                'founded_year'        => 2018,
                'company_website'     => 'https://www.studio49.id',
                'verification_status' => 'verified',
            ],
        ],
        'CV Bangun Persada' => [
            'email'        => 'info@bangunpersada.co.id',
            'location'     => 'Malang',
            'profile'      => [
                'company_name'        => 'CV Bangun Persada',
                'company_desc'        => 'Kontraktor dan konsultan arsitektur yang melayani proyek residensial dan komersial di wilayah Malang Raya. Mengutamakan kualitas gambar kerja dan ketepatan waktu.',
                'industry'            => 'Konstruksi & Konsultan',
                'company_size'        => '11-50 Karyawan',
                'business_fields'     => ['Konstruksi Residensial', 'Gambar Kerja', 'RAB & Tender'],
                'founded_year'        => 2009,
                'company_website'     => null,
                'verification_status' => 'verified',
            ],
        ],
        'PT Infrastruktor Muda' => [
            'email'        => 'hrd@infrastruktormuda.id',
            'location'     => 'Surabaya',
            'profile'      => [
                'company_name'        => 'PT Infrastruktor Muda',
                'company_desc'        => 'Konsultan MEP berpengalaman menangani koordinasi sistem mekanikal, elektrikal, dan plumbing untuk gedung bertingkat tinggi dan infrastruktur publik di Jawa Timur.',
                'industry'            => 'Engineering MEP',
                'company_size'        => '51-200 Karyawan',
                'business_fields'     => ['MEP Engineering', 'Koordinasi Sistem Bangunan', 'Infrastruktur'],
                'founded_year'        => 2011,
                'company_website'     => null,
                'verification_status' => 'verified',
            ],
        ],
        'Aedifica Nusantara' => [
            'email'        => 'karir@aedifica.co.id',
            'location'     => 'Jakarta Barat',
            'profile'      => [
                'company_name'        => 'PT Aedifica Nusantara',
                'company_desc'        => 'Perusahaan manajemen konstruksi dengan track record lebih dari 100 proyek besar. Mengelola proyek dari perencanaan hingga commissioning dengan standar internasional.',
                'industry'            => 'Manajemen Proyek & Konstruksi',
                'company_size'        => '501-1000 Karyawan',
                'business_fields'     => ['Project Management', 'Construction Management', 'Quantity Surveying'],
                'founded_year'        => 2005,
                'company_website'     => 'https://www.aedifica.co.id',
                'verification_status' => 'verified',
            ],
        ],
        'Render Studio ID' => [
            'email'        => 'hello@renderstudio.id',
            'location'     => 'Bandung',
            'profile'      => [
                'company_name'        => 'Render Studio ID',
                'company_desc'        => 'Studio visualisasi arsitektur profesional yang menghasilkan rendering fotorealistik dan animasi walkthrough berkualitas tinggi untuk biro arsitektur dan developer properti.',
                'industry'            => 'Visualisasi & CGI',
                'company_size'        => '1-10 Karyawan',
                'business_fields'     => ['3D Rendering', 'Animasi Arsitektur', 'CGI & Visualisasi'],
                'founded_year'        => 2020,
                'company_website'     => null,
                'verification_status' => 'verified',
            ],
        ],
    ];

    public function run(): void
    {
        // Build a lookup: lowongan.perusahaan (exact) → correct user_id
        $nameToUserId = [];

        foreach ($this->companyMap as $lowonganName => $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name'               => $lowonganName,
                    'password'           => Hash::make('password'),
                    'role'               => 'perusahaan',
                    'is_active'          => true,
                    'is_verified'        => true,
                    'email_verified_at'  => now(),
                    'location'           => $data['location'],
                ]
            );

            CompanyProfile::updateOrCreate(
                ['user_id' => $user->id],
                array_merge($data['profile'], [
                    'user_id'  => $user->id,
                    'location' => $data['location'],
                    'verified_at' => now(),
                ])
            );

            $nameToUserId[$lowonganName] = $user->id;
        }

        // Now update every lowongan: set user_id based on exact perusahaan name match
        foreach ($nameToUserId as $perusahaanName => $userId) {
            Lowongan::where('perusahaan', $perusahaanName)
                ->update(['user_id' => $userId]);
        }

        // Any lowongan that still doesn't have a valid mapping → set user_id = null
        // so the controller won't show a wrong "Lihat Profil" button
        $validUserIds = array_values($nameToUserId);

        Lowongan::whereNotNull('user_id')
            ->whereNotIn('user_id', $validUserIds)
            // Keep lowongan from PerusahaanSeeder (those users exist with proper profiles)
            ->whereNotIn('user_id', User::where('role', 'perusahaan')
                ->whereNotNull('email')
                ->whereIn('email', [
                    'hr@arkindo.studio',
                    'rekrutmen@grahadesign.id',
                    'jobs@kanainteriors.co',
                    'sdm@rekayasanusantara.co.id',
                    'info@tamanhijau.id',
                    'open@studio49.id',
                    'info@bangunpersada.co.id',
                    'hrd@infrastruktormuda.id',
                    'karir@aedifica.co.id',
                    'hello@renderstudio.id',
                ])->pluck('id')->toArray()
            )
            ->update(['user_id' => null]);

        $this->command->info('Lowongan user_id fixed: ' . count($nameToUserId) . ' companies mapped.');
    }
}
