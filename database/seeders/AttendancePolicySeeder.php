<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttendancePolicy;

class AttendancePolicySeeder extends Seeder
{
    public function run()
    {
        $policies = [
            // Company 1: PT Teknologi Maju Bersama
            [
                'company_id' => 1,
                'name' => 'Kebijakan Standar Office',
                'work_days' => json_encode([1, 2, 3, 4, 5]), // Senin-Jumat
                'start_time' => '09:00:00',
                'end_time' => '18:00:00',
                'break_duration' => 60,
                'late_tolerance' => 15,
                'early_checkout_tolerance' => 15,
                'overtime_after_minutes' => 480, // 8 jam
                'attendance_methods' => json_encode(['qr', 'gps', 'face_id']),
                'auto_checkout' => false,
                'is_default' => true,
            ],
            [
                'company_id' => 1,
                'name' => 'Flexible Working Hours',
                'work_days' => json_encode([1, 2, 3, 4, 5]),
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'break_duration' => 60,
                'late_tolerance' => 30,
                'early_checkout_tolerance' => 30,
                'overtime_after_minutes' => 480,
                'attendance_methods' => json_encode(['qr', 'gps']),
                'auto_checkout' => false,
                'is_default' => false,
            ],

            // Company 2: CV Kreatif Digital Solutions
            [
                'company_id' => 2,
                'name' => 'Creative Team Schedule',
                'work_days' => json_encode([1, 2, 3, 4, 5]),
                'start_time' => '10:00:00',
                'end_time' => '19:00:00',
                'break_duration' => 90,
                'late_tolerance' => 20,
                'early_checkout_tolerance' => 20,
                'overtime_after_minutes' => 540, // 9 jam
                'attendance_methods' => json_encode(['qr', 'gps', 'face_id']),
                'auto_checkout' => false,
                'is_default' => true,
            ],

            // Company 3: Toko Berkah Jaya
            [
                'company_id' => 3,
                'name' => 'Retail Store Hours',
                'work_days' => json_encode([1, 2, 3, 4, 5, 6]), // Senin-Sabtu
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'break_duration' => 60,
                'late_tolerance' => 10,
                'early_checkout_tolerance' => 10,
                'overtime_after_minutes' => 540, // 9 jam
                'attendance_methods' => json_encode(['qr', 'gps']),
                'auto_checkout' => true,
                'auto_checkout_time' => '17:30:00',
                'is_default' => true,
            ],

            // Company 4: PT Industri Manufaktur Nusantara
            [
                'company_id' => 4,
                'name' => 'Shift Pagi',
                'work_days' => json_encode([1, 2, 3, 4, 5, 6]),
                'start_time' => '06:00:00',
                'end_time' => '14:00:00',
                'break_duration' => 30,
                'late_tolerance' => 5,
                'early_checkout_tolerance' => 5,
                'overtime_after_minutes' => 480, // 8 jam
                'attendance_methods' => json_encode(['qr', 'gps']),
                'auto_checkout' => false,
                'is_default' => true,
            ],
            [
                'company_id' => 4,
                'name' => 'Shift Siang',
                'work_days' => json_encode([1, 2, 3, 4, 5, 6]),
                'start_time' => '14:00:00',
                'end_time' => '22:00:00',
                'break_duration' => 30,
                'late_tolerance' => 5,
                'early_checkout_tolerance' => 5,
                'overtime_after_minutes' => 480,
                'attendance_methods' => json_encode(['qr', 'gps']),
                'auto_checkout' => false,
                'is_default' => false,
            ],
            [
                'company_id' => 4,
                'name' => 'Shift Malam',
                'work_days' => json_encode([1, 2, 3, 4, 5, 6]),
                'start_time' => '22:00:00',
                'end_time' => '06:00:00',
                'break_duration' => 30,
                'late_tolerance' => 5,
                'early_checkout_tolerance' => 5,
                'overtime_after_minutes' => 480,
                'attendance_methods' => json_encode(['qr', 'gps']),
                'auto_checkout' => false,
                'is_default' => false,
            ],
        ];

        foreach ($policies as $policy) {
            AttendancePolicy::create($policy);
        }
    }
}
