<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    protected $defaultSettings = [
        // Company Information
        'company_name' => ['type' => 'text', 'label' => 'Company Name', 'default' => '', 'category' => 'company'],
        'company_address' => ['type' => 'textarea', 'label' => 'Company Address', 'default' => '', 'category' => 'company'],
        'company_phone' => ['type' => 'text', 'label' => 'Company Phone', 'default' => '', 'category' => 'company'],
        'company_email' => ['type' => 'email', 'label' => 'Company Email', 'default' => '', 'category' => 'company'],

        // Attendance Settings
        'attendance_start_time' => ['type' => 'time', 'label' => 'Default Start Time', 'default' => '09:00', 'category' => 'attendance'],
        'attendance_end_time' => ['type' => 'time', 'label' => 'Default End Time', 'default' => '18:00', 'category' => 'attendance'],
        'late_tolerance_minutes' => ['type' => 'number', 'label' => 'Late Tolerance (minutes)', 'default' => '15', 'category' => 'attendance'],
        'early_checkout_tolerance_minutes' => ['type' => 'number', 'label' => 'Early Checkout Tolerance (minutes)', 'default' => '15', 'category' => 'attendance'],
        'auto_checkout_enabled' => ['type' => 'boolean', 'label' => 'Auto Checkout Enabled', 'default' => '0', 'category' => 'attendance'],
        'auto_checkout_time' => ['type' => 'time', 'label' => 'Auto Checkout Time', 'default' => '18:00', 'category' => 'attendance'],

        // System Settings
        'timezone' => ['type' => 'select', 'label' => 'System Timezone', 'default' => 'Asia/Jakarta', 'options' => ['Asia/Jakarta' => 'WIB', 'Asia/Makassar' => 'WITA', 'Asia/Jayapura' => 'WIT', 'UTC' => 'UTC'], 'category' => 'system'],
        'date_format' => ['type' => 'select', 'label' => 'Date Format', 'default' => 'd/m/Y', 'options' => ['d/m/Y' => 'DD/MM/YYYY', 'm/d/Y' => 'MM/DD/YYYY', 'Y-m-d' => 'YYYY-MM-DD'], 'category' => 'system'],
        'time_format' => ['type' => 'select', 'label' => 'Time Format', 'default' => 'H:i', 'options' => ['H:i' => '24-hour (14:30)', 'h:i A' => '12-hour (2:30 PM)'], 'category' => 'system'],

        // Notification Settings
        'email_notifications' => ['type' => 'boolean', 'label' => 'Email Notifications', 'default' => '1', 'category' => 'notifications'],
        'sms_notifications' => ['type' => 'boolean', 'label' => 'SMS Notifications', 'default' => '0', 'category' => 'notifications'],
        'push_notifications' => ['type' => 'boolean', 'label' => 'Push Notifications', 'default' => '1', 'category' => 'notifications'],

        // Security Settings
        'password_min_length' => ['type' => 'number', 'label' => 'Minimum Password Length', 'default' => '8', 'category' => 'security'],
        'session_timeout' => ['type' => 'number', 'label' => 'Session Timeout (minutes)', 'default' => '480', 'category' => 'security'],
        'max_login_attempts' => ['type' => 'number', 'label' => 'Max Login Attempts', 'default' => '5', 'category' => 'security'],

        // Payroll Settings
        'payroll_calculation_method' => ['type' => 'select', 'label' => 'Payroll Calculation Method', 'default' => 'monthly', 'options' => ['monthly' => 'Monthly', 'weekly' => 'Weekly', 'biweekly' => 'Bi-weekly'], 'category' => 'payroll'],
        'overtime_rate_multiplier' => ['type' => 'number', 'label' => 'Overtime Rate Multiplier', 'default' => '1.5', 'step' => '0.1', 'category' => 'payroll'],
        'tax_calculation_enabled' => ['type' => 'boolean', 'label' => 'Tax Calculation Enabled', 'default' => '1', 'category' => 'payroll'],
    ];

    public function index()
    {
        $instansiId = Auth::user()->instansi_id;

        // Get existing settings
        $settings = Setting::where('instansi_id', $instansiId)
                          ->pluck('value', 'key')
                          ->toArray();

        // Merge with defaults
        $allSettings = [];
        foreach ($this->defaultSettings as $key => $config) {
            $allSettings[$key] = [
                'value' => $settings[$key] ?? $config['default'],
                'config' => $config
            ];
        }

        // Group by category
        $categories = [
            'company' => 'Company Information',
            'attendance' => 'Attendance Settings',
            'system' => 'System Settings',
            'notifications' => 'Notifications',
            'security' => 'Security Settings',
            'payroll' => 'Payroll Settings',
        ];

        return view('admin.settings.index', compact('allSettings', 'categories'));
    }

    public function update(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;

        // Validate all settings
        $rules = [];
        foreach ($this->defaultSettings as $key => $config) {
            $fieldRules = [];

            switch ($config['type']) {
                case 'text':
                case 'textarea':
                case 'select':
                    $fieldRules[] = 'nullable';
                    $fieldRules[] = 'string';
                    if (isset($config['max'])) {
                        $fieldRules[] = 'max:' . $config['max'];
                    }
                    break;
                case 'email':
                    $fieldRules[] = 'nullable';
                    $fieldRules[] = 'email';
                    break;
                case 'number':
                    $fieldRules[] = 'nullable';
                    $fieldRules[] = 'numeric';
                    if (isset($config['min'])) {
                        $fieldRules[] = 'min:' . $config['min'];
                    }
                    if (isset($config['max'])) {
                        $fieldRules[] = 'max:' . $config['max'];
                    }
                    break;
                case 'boolean':
                    $fieldRules[] = 'nullable';
                    $fieldRules[] = 'boolean';
                    break;
                case 'time':
                    $fieldRules[] = 'nullable';
                    $fieldRules[] = 'date_format:H:i';
                    break;
            }

            $rules[$key] = $fieldRules;
        }

        $validated = $request->validate($rules);

        // Update or create settings
        foreach ($validated as $key => $value) {
            if ($value !== null) {
                Setting::updateOrCreate(
                    [
                        'instansi_id' => $instansiId,
                        'key' => $key
                    ],
                    [
                        'value' => $value,
                        'type' => $this->defaultSettings[$key]['type']
                    ]
                );
            } else {
                // Remove setting if null (reset to default)
                Setting::where('instansi_id', $instansiId)
                      ->where('key', $key)
                      ->delete();
            }
        }

        // Clear cache
        Cache::forget("settings_{$instansiId}");

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'category' => 'nullable|string|in:' . implode(',', array_keys([
                'company' => 'Company Information',
                'attendance' => 'Attendance Settings',
                'system' => 'System Settings',
                'notifications' => 'Notifications',
                'security' => 'Security Settings',
                'payroll' => 'Payroll Settings',
            ]))
        ]);

        $instansiId = Auth::user()->instansi_id;

        if ($request->category) {
            // Reset specific category
            $categoryKeys = array_filter($this->defaultSettings, function($config) use ($request) {
                return $config['category'] === $request->category;
            });

            Setting::where('instansi_id', $instansiId)
                  ->whereIn('key', array_keys($categoryKeys))
                  ->delete();
        } else {
            // Reset all settings
            Setting::where('instansi_id', $instansiId)->delete();
        }

        // Clear cache
        Cache::forget("settings_{$instansiId}");

        $message = $request->category
            ? ucfirst($request->category) . ' settings reset to defaults.'
            : 'All settings reset to defaults.';

        return redirect()->back()->with('success', $message);
    }
}
