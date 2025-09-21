<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    public function run()
    {
        $branches = [
            // PT Teknologi Maju Bersama (Company ID: 1)
            [
                'company_id' => 1,
                'name' => 'Kantor Pusat Jakarta',
                'address' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'latitude' => -6.208763,
                'longitude' => 106.845599,
                'radius' => 100,
                'timezone' => 'Asia/Jakarta',
            ],
            [
                'company_id' => 1,
                'name' => 'Cabang Bekasi',
                'address' => 'Jl. Ahmad Yani No. 67, Bekasi Timur',
                'latitude' => -6.238270,
                'longitude' => 107.001567,
                'radius' => 150,
                'timezone' => 'Asia/Jakarta',
            ],
            [
                'company_id' => 1,
                'name' => 'Cabang Tangerang',
                'address' => 'Jl. Imam Bonjol No. 34, Tangerang Selatan',
                'latitude' => -6.297524,
                'longitude' => 106.718124,
                'radius' => 120,
                'timezone' => 'Asia/Jakarta',
            ],

            // CV Kreatif Digital Solutions (Company ID: 2)
            [
                'company_id' => 2,
                'name' => 'Kantor Pusat Bandung',
                'address' => 'Jl. Asia Afrika No. 45, Bandung',
                'latitude' => -6.921831,
                'longitude' => 107.607147,
                'radius' => 80,
                'timezone' => 'Asia/Jakarta',
            ],
            [
                'company_id' => 2,
                'name' => 'Co-working Space Dago',
                'address' => 'Jl. Ir. H. Juanda No. 123, Bandung',
                'latitude' => -6.895562,
                'longitude' => 107.613144,
                'radius' => 50,
                'timezone' => 'Asia/Jakarta',
            ],

            // Toko Berkah Jaya (Company ID: 3)
            [
                'company_id' => 3,
                'name' => 'Toko Utama',
                'address' => 'Jl. Diponegoro No. 88, Surabaya',
                'latitude' => -7.257472,
                'longitude' => 112.752088,
                'radius' => 75,
                'timezone' => 'Asia/Jakarta',
            ],

            // PT Industri Manufaktur (Company ID: 4)
            [
                'company_id' => 4,
                'name' => 'Pabrik Utama Cikarang',
                'address' => 'Kawasan Industri Jababeka Blok A-1, Cikarang',
                'latitude' => -6.296406,
                'longitude' => 107.154808,
                'radius' => 300,
                'timezone' => 'Asia/Jakarta',
            ],
            [
                'company_id' => 4,
                'name' => 'Gudang Distribusi Karawang',
                'address' => 'Jl. Raya Karawang-Jakarta KM 45, Karawang',
                'latitude' => -6.301206,
                'longitude' => 107.307809,
                'radius' => 200,
                'timezone' => 'Asia/Jakarta',
            ],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}
