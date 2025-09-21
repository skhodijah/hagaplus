<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanyTheme;

class CompanyThemeSeeder extends Seeder
{
    public function run()
    {
        $themes = [
            [
                'company_id' => 1,
                'primary_color' => '#3b82f6', // Blue
                'secondary_color' => '#64748b',
                'logo' => 'themes/company1/logo.png',
                'favicon' => 'themes/company1/favicon.ico',
                'custom_css' => '.navbar { background: linear-gradient(90deg, #3b82f6, #1e40af); }',
            ],
            [
                'company_id' => 2,
                'primary_color' => '#8b5cf6', // Purple
                'secondary_color' => '#a855f7',
                'logo' => 'themes/company2/logo.png',
                'favicon' => 'themes/company2/favicon.ico',
                'custom_css' => '.header { border-bottom: 3px solid #8b5cf6; }',
            ],
            [
                'company_id' => 3,
                'primary_color' => '#f59e0b', // Amber
                'secondary_color' => '#d97706',
                'logo' => 'themes/company3/logo.png',
                'favicon' => 'themes/company3/favicon.ico',
                'custom_css' => '.btn-primary { background-color: #f59e0b; border-color: #f59e0b; }',
            ],
            [
                'company_id' => 4,
                'primary_color' => '#ef4444', // Red
                'secondary_color' => '#dc2626',
                'logo' => 'themes/company4/logo.png',
                'favicon' => 'themes/company4/favicon.ico',
                'custom_css' => '.sidebar { background: linear-gradient(180deg, #ef4444, #dc2626); }',
            ],
        ];

        foreach ($themes as $theme) {
            CompanyTheme::create($theme);
        }
    }
}
