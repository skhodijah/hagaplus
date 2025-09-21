<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use Carbon\Carbon;

class CompanySeeder extends Seeder
{
    public function run()
    {
        $companies = [
            [
                'name' => 'PT Teknologi Maju Bersama',
                'email' => 'admin@teknologimaju.com',
                'phone' => '021-5555-0001',
                'address' => 'Jl. Sudirman No. 123, Jakarta Pusat, DKI Jakarta',
                'package_id' => 3, // Enterprise
                'subscription_start' => Carbon::now()->subDays(15),
                'subscription_end' => Carbon::now()->addDays(15),
                'max_employees' => 200,
                'max_branches' => 20,
                'settings' => json_encode([
                    'timezone' => 'Asia/Jakarta',
                    'date_format' => 'd/m/Y',
                    'time_format' => 'H:i',
                    'currency' => 'IDR'
                ]),
            ],
            [
                'name' => 'CV Kreatif Digital Solutions',
                'email' => 'hr@kreatifdigital.com',
                'phone' => '022-3333-0002',
                'address' => 'Jl. Asia Afrika No. 45, Bandung, Jawa Barat',
                'package_id' => 2, // Business
                'subscription_start' => Carbon::now()->subDays(10),
                'subscription_end' => Carbon::now()->addDays(20),
                'max_employees' => 50,
                'max_branches' => 5,
                'settings' => json_encode([
                    'timezone' => 'Asia/Jakarta',
                    'date_format' => 'd-m-Y',
                    'time_format' => 'H:i:s'
                ]),
            ],
            [
                'name' => 'Toko Berkah Jaya',
                'email' => 'owner@berkahjaya.com',
                'phone' => '031-7777-0003',
                'address' => 'Jl. Diponegoro No. 88, Surabaya, Jawa Timur',
                'package_id' => 1, // Starter
                'subscription_start' => Carbon::now()->subDays(5),
                'subscription_end' => Carbon::now()->addDays(25),
                'max_employees' => 10,
                'max_branches' => 1,
                'settings' => json_encode([
                    'timezone' => 'Asia/Jakarta',
                    'working_days' => [1, 2, 3, 4, 5, 6] // Senin-Sabtu
                ]),
            ],
            [
                'name' => 'PT Industri Manufaktur Nusantara',
                'email' => 'hrd@manufakturnusantara.co.id',
                'phone' => '024-9999-0004',
                'address' => 'Kawasan Industri Jababeka, Cikarang, Jawa Barat',
                'package_id' => 4, // Corporate
                'subscription_start' => Carbon::now()->subDays(20),
                'subscription_end' => Carbon::now()->addDays(10),
                'max_employees' => 1000,
                'max_branches' => 50,
                'settings' => json_encode([
                    'timezone' => 'Asia/Jakarta',
                    'shift_based' => true,
                    'overtime_enabled' => true
                ]),
            ]
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}
