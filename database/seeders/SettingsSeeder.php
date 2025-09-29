<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // System Settings
            [
                'key' => 'system.app_name',
                'value' => 'HagaPlus',
                'type' => 'string',
                'category' => 'system',
            ],
            [
                'key' => 'system.app_version',
                'value' => '1.0.0',
                'type' => 'string',
                'category' => 'system',
            ],
            [
                'key' => 'system.maintenance_mode',
                'value' => 'false',
                'type' => 'boolean',
                'category' => 'system',
            ],
            [
                'key' => 'system.timezone',
                'value' => 'Asia/Jakarta',
                'type' => 'string',
                'category' => 'system',
            ],

            // WhatsApp Settings
            [
                'key' => 'whatsapp.superadmin_phone',
                'value' => '6281234567890',
                'type' => 'string',
                'category' => 'notification',
            ],
            [
                'key' => 'whatsapp.api_url',
                'value' => 'https://api.whatsapp.com/send',
                'type' => 'string',
                'category' => 'notification',
            ],

            // Email Settings
            [
                'key' => 'email.from_address',
                'value' => 'noreply@hagaplus.com',
                'type' => 'string',
                'category' => 'notification',
            ],
            [
                'key' => 'email.from_name',
                'value' => 'HagaPlus',
                'type' => 'string',
                'category' => 'notification',
            ],

            // Subscription Settings
            [
                'key' => 'subscription.trial_days',
                'value' => '14',
                'type' => 'integer',
                'category' => 'subscription',
            ],
            [
                'key' => 'subscription.grace_period_days',
                'value' => '7',
                'type' => 'integer',
                'category' => 'subscription',
            ],
            [
                'key' => 'subscription.auto_suspend',
                'value' => 'true',
                'type' => 'boolean',
                'category' => 'subscription',
            ],

            // Security Settings
            [
                'key' => 'security.session_timeout',
                'value' => '7200', // 2 hours
                'type' => 'integer',
                'category' => 'security',
            ],
            [
                'key' => 'security.password_min_length',
                'value' => '8',
                'type' => 'integer',
                'category' => 'security',
            ],
            [
                'key' => 'security.two_factor_required',
                'value' => 'false',
                'type' => 'boolean',
                'category' => 'security',
            ],

            // File Upload Settings
            [
                'key' => 'upload.max_file_size',
                'value' => '5242880', // 5MB
                'type' => 'integer',
                'category' => 'upload',
            ],
            [
                'key' => 'upload.allowed_extensions',
                'value' => 'jpg,jpeg,png,pdf,doc,docx',
                'type' => 'string',
                'category' => 'upload',
            ],

            // Attendance Settings
            [
                'key' => 'attendance.work_start_time',
                'value' => '08:00',
                'type' => 'string',
                'category' => 'attendance',
            ],
            [
                'key' => 'attendance.work_end_time',
                'value' => '17:00',
                'type' => 'string',
                'category' => 'attendance',
            ],
            [
                'key' => 'attendance.grace_period_minutes',
                'value' => '15',
                'type' => 'integer',
                'category' => 'attendance',
            ],
            [
                'key' => 'attendance.auto_checkout',
                'value' => 'true',
                'type' => 'boolean',
                'category' => 'attendance',
            ],

            // Payroll Settings
            [
                'key' => 'payroll.cutoff_date',
                'value' => '25',
                'type' => 'integer',
                'category' => 'payroll',
            ],
            [
                'key' => 'payroll.payment_date',
                'value' => '30',
                'type' => 'integer',
                'category' => 'payroll',
            ],
            [
                'key' => 'payroll.currency',
                'value' => 'IDR',
                'type' => 'string',
                'category' => 'payroll',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}