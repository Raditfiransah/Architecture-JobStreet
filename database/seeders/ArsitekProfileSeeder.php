<?php

namespace Database\Seeders;

use App\Models\ArsitekProfile;
use App\Models\Portofolio;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ArsitekProfileSeeder extends Seeder
{
    public function run(): void
    {
        $arsitek = [
            [
                'user' => [
                    'name'              => 'Rizky Aditya Pratama',
                    'email'             => 'rizky.aditya@example.com',
                    'phone'             => '081234567801',
                    'location'          => 'Jakarta Selatan, DKI Jakarta',
                    'avatar_url'        => null,
                ],
                'profile' => [
                    'first_name'            => 'Rizky',
                    'last_name'             => 'Aditya Pratama',
                    'bio'                   => 'Arsitek dengan spesialisasi desain residensial mewah modern kontemporer. Berpengalaman mengerjakan lebih dari 50 proyek vila dan rumah tinggal premium di seluruh Indonesia.',
                    'specialization'        => 'Residensial Mewah',
                    'years_experience'      => 8,
                    'is_student'            => false,
                    'school'                => 'Universitas Indonesia',
                    'degree_type'           => 'S1 Arsitektur',
                    'status_pekerjaan'      => 'Arsitek Profesional (IAI)',
                    'location'              => 'Jakarta Selatan',
                    'software_skills'       => ['AutoCAD', 'Revit', 'SketchUp', 'Lumion', 'V-Ray', 'Photoshop'],
                    'preferences'           => ['Residensial', 'Desain Interior', 'Renovasi'],
                    'license_number'        => 'IAI-JKT-2018-001234',
                    'external_portfolio_url'=> 'https://www.rizkyarsitek.com',
                    'verification_status'   => 'verified',
                    'verified_at'           => now()->subMonths(6),
                ],
                'portofolios' => [
                    [
                        'title'        => 'Vila Modern Tropis Bali',
                        'description'  => 'Desain vila 3 kamar dengan konsep terbuka dan material bambu lokal di Seminyak, Bali.',
                        'project_date' => '2024-03-15',
                        'order'        => 1,
                    ],
                    [
                        'title'        => 'Rumah Minimalis Kontemporer BSD',
                        'description'  => 'Rumah tinggal 2 lantai, 4 kamar tidur dengan konsep open space dan taman dalam.',
                        'project_date' => '2023-09-20',
                        'order'        => 2,
                    ],
                    [
                        'title'        => 'Renovasi Apartemen Premium Kemang',
                        'description'  => 'Renovasi total unit apartemen 120m² menjadi konsep industrial-modern.',
                        'project_date' => '2023-06-01',
                        'order'        => 3,
                    ],
                ],
            ],
            [
                'user' => [
                    'name'     => 'Sari Dewi Kusuma',
                    'email'    => 'sari.dewi@example.com',
                    'phone'    => '081234567802',
                    'location' => 'Surabaya, Jawa Timur',
                ],
                'profile' => [
                    'first_name'            => 'Sari',
                    'last_name'             => 'Dewi Kusuma',
                    'bio'                   => 'Desainer interior berpengalaman dengan fokus pada konsep Japandi dan Scandinavian untuk ruang komersial. Telah mengerjakan 30+ proyek kafe dan restoran.',
                    'specialization'        => 'Desain Interior Komersial',
                    'years_experience'      => 5,
                    'is_student'            => false,
                    'school'                => 'Institut Teknologi Sepuluh Nopember',
                    'degree_type'           => 'S1 Arsitektur',
                    'status_pekerjaan'      => 'Freelance',
                    'location'              => 'Surabaya',
                    'software_skills'       => ['3DS Max', 'V-Ray', 'AutoCAD', 'SketchUp', 'Adobe Illustrator'],
                    'preferences'           => ['Desain Interior', 'Komersial', 'Renovasi'],
                    'license_number'        => null,
                    'external_portfolio_url'=> 'https://www.behance.net/saridewi',
                    'verification_status'   => 'verified',
                    'verified_at'           => now()->subMonths(3),
                ],
                'portofolios' => [
                    [
                        'title'        => 'Kafe Japandi "Sakura Brew" Surabaya',
                        'description'  => 'Desain interior kafe 200m² dengan konsep Japandi, menggunakan material kayu jati dan rotan alami.',
                        'project_date' => '2024-01-10',
                        'order'        => 1,
                    ],
                    [
                        'title'        => 'Restoran "Warung Bumi" Malang',
                        'description'  => 'Konsep open-air dining dengan sentuhan etnik Jawa modern untuk kapasitas 80 orang.',
                        'project_date' => '2023-11-05',
                        'order'        => 2,
                    ],
                ],
            ],
            [
                'user' => [
                    'name'     => 'Bimo Wahyu Nugroho',
                    'email'    => 'bimo.wahyu@example.com',
                    'phone'    => '081234567803',
                    'location' => 'Yogyakarta, DIY',
                ],
                'profile' => [
                    'first_name'            => 'Bimo',
                    'last_name'             => 'Wahyu Nugroho',
                    'bio'                   => 'Urban Planner muda berfokus pada perancangan kawasan transit-oriented development (TOD) dan kota pintar. Aktif berkontribusi di berbagai sayembara nasional.',
                    'specialization'        => 'Urban Planning & TOD',
                    'years_experience'      => 3,
                    'is_student'            => false,
                    'school'                => 'Universitas Gadjah Mada',
                    'degree_type'           => 'S2 Arsitektur',
                    'status_pekerjaan'      => 'Available',
                    'location'              => 'Yogyakarta',
                    'software_skills'       => ['AutoCAD', 'ArcGIS', 'SketchUp', 'Adobe Premiere', 'Revit'],
                    'preferences'           => ['Urban Planning', 'Kawasan', 'Transportasi'],
                    'license_number'        => 'IAI-DIY-2023-005678',
                    'external_portfolio_url'=> 'https://www.linkedin.com/in/bimowahyu',
                    'verification_status'   => 'pending',
                    'verified_at'           => null,
                ],
                'portofolios' => [
                    [
                        'title'        => 'Masterplan Kawasan TOD Stasiun Tugu Yogyakarta',
                        'description'  => 'Perancangan kawasan mixed-use radius 500m dari Stasiun Tugu dengan konsep walkable & bikeable city.',
                        'project_date' => '2024-05-20',
                        'order'        => 1,
                    ],
                ],
            ],
            [
                'user' => [
                    'name'     => 'Cahya Puspita Rini',
                    'email'    => 'cahya.puspita@example.com',
                    'phone'    => '081234567804',
                    'location' => 'Bandung, Jawa Barat',
                ],
                'profile' => [
                    'first_name'            => 'Cahya',
                    'last_name'             => 'Puspita Rini',
                    'bio'                   => 'Landscape Architect berpengalaman merancang taman dan ruang publik berskala besar. Mengutamakan pendekatan biophilic dan material lokal ramah lingkungan.',
                    'specialization'        => 'Arsitektur Lanskap',
                    'years_experience'      => 6,
                    'is_student'            => false,
                    'school'                => 'Institut Pertanian Bogor',
                    'degree_type'           => 'S1 Arsitektur Lanskap',
                    'status_pekerjaan'      => 'Arsitek Profesional (IAI)',
                    'location'              => 'Bandung',
                    'software_skills'       => ['AutoCAD', 'Lumion', 'SketchUp', 'Adobe InDesign', 'ArcGIS'],
                    'preferences'           => ['Lansekap & Taman', 'Residensial', 'Urban Planning'],
                    'license_number'        => 'IAI-BJB-2020-007890',
                    'external_portfolio_url'=> 'https://www.cahyalandscape.id',
                    'verification_status'   => 'verified',
                    'verified_at'           => now()->subMonths(8),
                ],
                'portofolios' => [
                    [
                        'title'        => 'Taman Kota Alun-Alun Cianjur',
                        'description'  => 'Revitalisasi taman kota 2 hektar dengan konsep therapeutic garden dan area bermain ramah disabilitas.',
                        'project_date' => '2023-12-01',
                        'order'        => 1,
                    ],
                    [
                        'title'        => 'Landscaping Perumahan Grand Legenda Bekasi',
                        'description'  => 'Desain lanskap cluster perumahan premium 50 kaveling dengan sistem drainase biofilter.',
                        'project_date' => '2023-07-15',
                        'order'        => 2,
                    ],
                    [
                        'title'        => 'Roof Garden Hotel Aryaduta Bandung',
                        'description'  => 'Desain taman atap seluas 500m² dengan konsep urban farming dan area relaksasi.',
                        'project_date' => '2024-02-28',
                        'order'        => 3,
                    ],
                ],
            ],
            [
                'user' => [
                    'name'     => 'Dika Firmansyah',
                    'email'    => 'dika.firmansyah@example.com',
                    'phone'    => '081234567805',
                    'location' => 'Bali',
                ],
                'profile' => [
                    'first_name'            => 'Dika',
                    'last_name'             => 'Firmansyah',
                    'bio'                   => 'BIM Specialist dengan sertifikasi Autodesk Revit berpengalaman di proyek gedung tinggi dan infrastruktur. Ahli dalam clash detection dan manajemen data BIM.',
                    'specialization'        => 'BIM & Infrastruktur',
                    'years_experience'      => 4,
                    'is_student'            => false,
                    'school'                => 'Universitas Udayana',
                    'degree_type'           => 'S1 Arsitektur',
                    'status_pekerjaan'      => 'Hired',
                    'location'              => 'Denpasar, Bali',
                    'software_skills'       => ['Revit', 'Navisworks', 'AutoCAD', 'Dynamo', 'BIM 360'],
                    'preferences'           => ['Komersial', 'Infrastruktur', 'BIM'],
                    'license_number'        => 'AUTODESK-BIM-CERT-2022',
                    'external_portfolio_url'=> null,
                    'verification_status'   => 'verified',
                    'verified_at'           => now()->subMonths(2),
                ],
                'portofolios' => [],
            ],
            [
                'user' => [
                    'name'     => 'Elisa Mariana Santoso',
                    'email'    => 'elisa.mariana@example.com',
                    'phone'    => '081234567806',
                    'location' => 'Semarang, Jawa Tengah',
                ],
                'profile' => [
                    'first_name'            => 'Elisa',
                    'last_name'             => 'Mariana Santoso',
                    'bio'                   => 'Arsitek muda fresh graduate yang antusias dalam arsitektur vernakular dan bangunan hemat energi. Aktif belajar dan berpengalaman magang di biro arsitektur internasional.',
                    'specialization'        => 'Arsitektur Vernakular & Hijau',
                    'years_experience'      => 1,
                    'is_student'            => false,
                    'school'                => 'Universitas Diponegoro',
                    'degree_type'           => 'S1 Arsitektur',
                    'status_pekerjaan'      => 'Available',
                    'location'              => 'Semarang',
                    'software_skills'       => ['SketchUp', 'AutoCAD', 'Revit', 'Adobe Photoshop'],
                    'preferences'           => ['Residensial', 'Renovasi', 'Desain Interior'],
                    'license_number'        => null,
                    'external_portfolio_url'=> 'https://www.elisaarch.my.id',
                    'verification_status'   => 'unverified',
                    'verified_at'           => null,
                ],
                'portofolios' => [
                    [
                        'title'        => 'Tugas Akhir: Rumah Bambu Zero-Waste Wonosobo',
                        'description'  => 'Perancangan rumah tinggal dengan sistem konstruksi bambu modular yang dapat dibongkar-pasang tanpa limbah.',
                        'project_date' => '2024-06-01',
                        'order'        => 1,
                    ],
                ],
            ],
            [
                'user' => [
                    'name'     => 'Fajar Eka Perdana',
                    'email'    => 'fajar.eka@example.com',
                    'phone'    => '081234567807',
                    'location' => 'Medan, Sumatera Utara',
                ],
                'profile' => [
                    'first_name'            => 'Fajar',
                    'last_name'             => 'Eka Perdana',
                    'bio'                   => 'Senior arsitek berpengalaman 12 tahun di proyek-proyek hotel bintang lima dan resort mewah di kawasan ASEAN. Ahli dalam konsep hospitality architecture.',
                    'specialization'        => 'Hospitality & Resort',
                    'years_experience'      => 12,
                    'is_student'            => false,
                    'school'                => 'Universitas Sumatera Utara',
                    'degree_type'           => 'S1 Arsitektur',
                    'status_pekerjaan'      => 'Arsitek Profesional (IAI)',
                    'location'              => 'Medan',
                    'software_skills'       => ['AutoCAD', 'Revit', 'SketchUp', 'Lumion', '3DS Max', 'ArchiCAD'],
                    'preferences'           => ['Komersial', 'Residensial Mewah', 'Hospitality'],
                    'license_number'        => 'IAI-SU-2014-009123',
                    'external_portfolio_url'=> 'https://www.fajararchitects.com',
                    'verification_status'   => 'verified',
                    'verified_at'           => now()->subYear(),
                ],
                'portofolios' => [
                    [
                        'title'        => 'Boutique Hotel "The Dairi" Danau Toba',
                        'description'  => 'Desain boutique hotel 20 kamar dengan konsep arsitektur Batak modern menghadap Danau Toba.',
                        'project_date' => '2023-08-10',
                        'order'        => 1,
                    ],
                    [
                        'title'        => 'Resort Overwater Bungalow Raja Ampat',
                        'description'  => 'Perancangan 12 unit bungalow di atas air dengan material kayu ulin bersertifikasi FSC.',
                        'project_date' => '2022-04-20',
                        'order'        => 2,
                    ],
                ],
            ],
            [
                'user' => [
                    'name'     => 'Gita Nirmala Hapsari',
                    'email'    => 'gita.nirmala@example.com',
                    'phone'    => '081234567808',
                    'location' => 'Malang, Jawa Timur',
                ],
                'profile' => [
                    'first_name'            => 'Gita',
                    'last_name'             => 'Nirmala Hapsari',
                    'bio'                   => 'Arsitek perempuan aktif yang berfokus pada inklusivitas dan desain universal (universal design). Konsultan tetap untuk beberapa proyek fasilitas publik pemerintah.',
                    'specialization'        => 'Fasilitas Publik & Universal Design',
                    'years_experience'      => 7,
                    'is_student'            => false,
                    'school'                => 'Universitas Brawijaya',
                    'degree_type'           => 'S2 Arsitektur',
                    'status_pekerjaan'      => 'Arsitek Profesional (IAI)',
                    'location'              => 'Malang',
                    'software_skills'       => ['AutoCAD', 'Revit', 'SketchUp', 'Adobe InDesign', 'QGIS'],
                    'preferences'           => ['Fasilitas Publik', 'Pendidikan', 'Kesehatan'],
                    'license_number'        => 'IAI-JTM-2019-011456',
                    'external_portfolio_url'=> 'https://www.gitaarch.id',
                    'verification_status'   => 'verified',
                    'verified_at'           => now()->subMonths(10),
                ],
                'portofolios' => [
                    [
                        'title'        => 'Perpustakaan Umum Kota Malang',
                        'description'  => 'Desain perpustakaan 4 lantai dengan konsep ramah disabilitas, taman baca outdoor, dan ruang komunitas.',
                        'project_date' => '2024-04-05',
                        'order'        => 1,
                    ],
                    [
                        'title'        => 'Puskesmas Plus Ramah Lansia',
                        'description'  => 'Redesain puskesmas dengan sirkulasi yang dioptimalkan untuk lansia dan pengguna kursi roda.',
                        'project_date' => '2023-10-15',
                        'order'        => 2,
                    ],
                ],
            ],
        ];

        foreach ($arsitek as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['user']['email']],
                array_merge($data['user'], [
                    'password'           => Hash::make('password'),
                    'role'               => 'arsitek',
                    'is_active'          => true,
                    'is_verified'        => true,
                    'email_verified_at'  => now(),
                ])
            );

            ArsitekProfile::updateOrCreate(
                ['user_id' => $user->id],
                array_merge($data['profile'], ['user_id' => $user->id])
            );

            foreach ($data['portofolios'] as $i => $porto) {
                Portofolio::updateOrCreate(
                    ['user_id' => $user->id, 'title' => $porto['title']],
                    array_merge($porto, ['user_id' => $user->id])
                );
            }
        }
    }
}
