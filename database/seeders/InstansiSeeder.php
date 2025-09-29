<?php

namespace Database\Seeders;

use App\Models\SuperAdmin\Instansi;
use App\Models\SuperAdmin\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InstansiSeeder extends Seeder
{
    public function run(): void
    {
        $packages = Package::all();

        $instansis = [
            [
                'nama_instansi' => 'PT. Teknologi Maju',
                'subdomain' => 'teknologi-maju',
                'email' => 'admin@teknologimaju.com',
                'phone' => '021-12345678',
                'address' => 'Jl. Teknologi No. 123, Jakarta Pusat',
                'status_langganan' => 'active',
                'package_id' => $packages->where('name', 'Standard')->first()?->id,
                'subscription_start' => now()->subDays(15),
                'subscription_end' => now()->addDays(15),
                'is_active' => true,
                'max_employees' => 50,
                'max_branches' => 3,
            ],
            [
                'nama_instansi' => 'CV. Kreatif Digital',
                'subdomain' => 'kreatif-digital',
                'email' => 'info@kreatifdigital.com',
                'phone' => '022-87654321',
                'address' => 'Jl. Kreatif No. 45, Bandung',
                'status_langganan' => 'active',
                'package_id' => $packages->where('name', 'Basic')->first()?->id,
                'subscription_start' => now()->subDays(5),
                'subscription_end' => now()->addDays(25),
                'is_active' => true,
                'max_employees' => 10,
                'max_branches' => 1,
            ],
            [
                'nama_instansi' => 'PT. Global Solutions',
                'subdomain' => 'global-solutions',
                'email' => 'contact@globalsolutions.id',
                'phone' => '031-5551234',
                'address' => 'Jl. Global No. 67, Surabaya',
                'status_langganan' => 'inactive',
                'package_id' => null,
                'subscription_start' => null,
                'subscription_end' => null,
                'is_active' => true,
                'max_employees' => 10,
                'max_branches' => 1,
            ],
            [
                'nama_instansi' => 'Universitas Teknologi Indonesia',
                'subdomain' => 'uti-ac-id',
                'email' => 'admin@uti.ac.id',
                'phone' => '024-7778899',
                'address' => 'Jl. Pendidikan No. 89, Semarang',
                'status_langganan' => 'active',
                'package_id' => $packages->where('name', 'Premium')->first()?->id,
                'subscription_start' => now()->subDays(30),
                'subscription_end' => now()->addDays(0), // Expired today
                'is_active' => true,
                'max_employees' => 200,
                'max_branches' => 10,
            ],
            [
                'nama_instansi' => 'PT. Inovasi Nusantara',
                'subdomain' => 'inovasi-nusantara',
                'email' => 'hr@inovasinusantara.com',
                'phone' => '061-4445566',
                'address' => 'Jl. Inovasi No. 101, Medan',
                'status_langganan' => 'active',
                'package_id' => $packages->where('name', 'Enterprise')->first()?->id,
                'subscription_start' => now()->subDays(10),
                'subscription_end' => now()->addDays(20),
                'is_active' => true,
                'max_employees' => 1000,
                'max_branches' => 50,
            ],
        ];

        foreach ($instansis as $instansi) {
            Instansi::create($instansi);
        }
    }
}