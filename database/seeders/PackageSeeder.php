<?php

namespace Database\Seeders;

use App\Models\SuperAdmin\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Basic',
                'description' => 'Paket dasar untuk instansi kecil dengan fitur essential',
                'price' => 50000,
                'duration_days' => 30,
                'max_employees' => 10,
                'max_branches' => 1,
                'features' => [
                    'attendance_management',
                    'basic_reporting',
                    'qr_code_attendance',
                    'mobile_app_access'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Standard',
                'description' => 'Paket standar untuk instansi menengah dengan fitur lengkap',
                'price' => 100000,
                'duration_days' => 30,
                'max_employees' => 50,
                'max_branches' => 3,
                'features' => [
                    'attendance_management',
                    'payroll_management',
                    'advanced_reporting',
                    'qr_code_attendance',
                    'gps_tracking',
                    'face_recognition',
                    'leave_management',
                    'mobile_app_access',
                    'web_dashboard',
                    'email_notifications'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Premium',
                'description' => 'Paket premium untuk instansi besar dengan semua fitur',
                'price' => 200000,
                'duration_days' => 30,
                'max_employees' => 200,
                'max_branches' => 10,
                'features' => [
                    'attendance_management',
                    'payroll_management',
                    'advanced_reporting',
                    'qr_code_attendance',
                    'gps_tracking',
                    'face_recognition',
                    'leave_management',
                    'mobile_app_access',
                    'web_dashboard',
                    'email_notifications',
                    'sms_notifications',
                    'api_integration',
                    'custom_branding',
                    'priority_support',
                    'advanced_analytics',
                    'bulk_operations'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Enterprise',
                'description' => 'Paket enterprise untuk organisasi besar dengan fitur custom',
                'price' => 500000,
                'duration_days' => 30,
                'max_employees' => 1000,
                'max_branches' => 50,
                'features' => [
                    'attendance_management',
                    'payroll_management',
                    'advanced_reporting',
                    'qr_code_attendance',
                    'gps_tracking',
                    'face_recognition',
                    'leave_management',
                    'mobile_app_access',
                    'web_dashboard',
                    'email_notifications',
                    'sms_notifications',
                    'api_integration',
                    'custom_branding',
                    'priority_support',
                    'advanced_analytics',
                    'bulk_operations',
                    'multi_company_support',
                    'custom_integrations',
                    'dedicated_support',
                    'on_premise_option'
                ],
                'is_active' => true,
            ],
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}
