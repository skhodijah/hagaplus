<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    public function run()
    {
        $packages = [
            [
                'name' => 'Starter',
                'description' => 'Paket dasar untuk usaha kecil dengan fitur absen QR Code dan GPS tracking',
                'price' => 49000.00,
                'duration_days' => 30,
                'max_employees' => 10,
                'max_branches' => 1,
                'features' => json_encode(['qr', 'gps', 'basic_reports']),
                'is_active' => true,
            ],
            [
                'name' => 'Business',
                'description' => 'Paket lengkap untuk usaha menengah dengan multi cabang dan Face ID',
                'price' => 149000.00,
                'duration_days' => 30,
                'max_employees' => 50,
                'max_branches' => 5,
                'features' => json_encode(['qr', 'gps', 'face_id', 'shift_management', 'leave_management', 'payroll']),
                'is_active' => true,
            ],
            [
                'name' => 'Enterprise',
                'description' => 'Paket premium untuk perusahaan besar dengan fitur lengkap dan laporan advanced',
                'price' => 299000.00,
                'duration_days' => 30,
                'max_employees' => 200,
                'max_branches' => 20,
                'features' => json_encode(['qr', 'gps', 'face_id', 'shift_management', 'leave_management', 'payroll', 'advanced_reports', 'api_access', 'custom_branding']),
                'is_active' => true,
            ],
            [
                'name' => 'Corporate',
                'description' => 'Solusi enterprise dengan unlimited employees dan custom features',
                'price' => 599000.00,
                'duration_days' => 30,
                'max_employees' => 1000,
                'max_branches' => 50,
                'features' => json_encode(['all_features', 'unlimited_employees', 'custom_integration', 'dedicated_support']),
                'is_active' => true,
            ]
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}
