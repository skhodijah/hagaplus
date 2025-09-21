<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            [
                'key_name' => 'app_name',
                'value' => 'HagaPlus',
                'description' => 'Nama aplikasi',
                'type' => 'string',
                'is_public' => true,
            ],
            [
                'key_name' => 'app_version',
                'value' => '1.0.0',
                'description' => 'Versi aplikasi',
                'type' => 'string',
                'is_public' => true,
            ],
            [
                'key_name' => 'default_timezone',
                'value' => 'Asia/Jakarta',
                'description' => 'Timezone default sistem',
                'type' => 'string',
                'is_public' => false,
            ],
            [
                'key_name' => 'max_login_attempts',
                'value' => '5',
                'description' => 'Maksimal percobaan login',
                'type' => 'integer',
                'is_public' => false,
            ],
            [
                'key_name' => 'session_timeout',
                'value' => '120',
                'description' => 'Session timeout dalam menit',
                'type' => 'integer',
                'is_public' => false,
            ],
            [
                'key_name' => 'allow_mobile_web',
                'value' => 'true',
                'description' => 'Izinkan akses via mobile web',
                'type' => 'boolean',
                'is_public' => true,
            ],
            [
                'key_name' => 'face_recognition_enabled',
                'value' => 'true',
                'description' => 'Aktifkan fitur face recognition',
                'type' => 'boolean',
                'is_public' => true,
            ],
            [
                'key_name' => 'qr_code_refresh_minutes',
                'value' => '1440',
                'description' => 'QR Code refresh interval dalam menit (24 jam)',
                'type' => 'integer',
                'is_public' => false,
            ],
            [
                'key_name' => 'attendance_photo_required',
                'value' => 'true',
                'description' => 'Wajibkan foto saat absen',
                'type' => 'boolean',
                'is_public' => true,
            ],
            [
                'key_name' => 'payroll_auto_generate',
                'value' => 'false',
                'description' => 'Generate payroll otomatis setiap bulan',
                'type' => 'boolean',
                'is_public' => false,
            ],
            [
                'key_name' => 'notification_channels',
                'value' => '["email", "whatsapp", "push"]',
                'description' => 'Channel notifikasi yang tersedia',
                'type' => 'json',
                'is_public' => true,
            ],
            [
                'key_name' => 'subscription_reminder_days',
                'value' => '7',
                'description' => 'Reminder subscription berakhir (hari sebelumnya)',
                'type' => 'integer',
                'is_public' => false,
            ],
        ];

        foreach ($settings as $setting) {
            SystemSetting::create($setting);
        }
    }
}
