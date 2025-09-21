<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Holiday;

class HolidaySeeder extends Seeder
{
    public function run()
    {
        // Libur Nasional Indonesia 2024
        $nationalHolidays = [
            ['name' => 'Tahun Baru', 'date' => '2024-01-01', 'type' => 'national'],
            ['name' => 'Isra Mikraj', 'date' => '2024-02-08', 'type' => 'religious'],
            ['name' => 'Hari Raya Nyepi', 'date' => '2024-03-11', 'type' => 'religious'],
            ['name' => 'Wafat Isa Almasih', 'date' => '2024-03-29', 'type' => 'religious'],
            ['name' => 'Idul Fitri', 'date' => '2024-04-10', 'type' => 'religious'],
            ['name' => 'Idul Fitri', 'date' => '2024-04-11', 'type' => 'religious'],
            ['name' => 'Hari Buruh', 'date' => '2024-05-01', 'type' => 'national'],
            ['name' => 'Hari Raya Waisak', 'date' => '2024-05-23', 'type' => 'religious'],
            ['name' => 'Kenaikan Isa Almasih', 'date' => '2024-05-09', 'type' => 'religious'],
            ['name' => 'Hari Lahir Pancasila', 'date' => '2024-06-01', 'type' => 'national'],
            ['name' => 'Idul Adha', 'date' => '2024-06-17', 'type' => 'religious'],
            ['name' => 'Tahun Baru Islam', 'date' => '2024-07-07', 'type' => 'religious'],
            ['name' => 'Kemerdekaan RI', 'date' => '2024-08-17', 'type' => 'national'],
            ['name' => 'Maulid Nabi Muhammad', 'date' => '2024-09-16', 'type' => 'religious'],
            ['name' => 'Hari Natal', 'date' => '2024-12-25', 'type' => 'national'],
        ];

        foreach ($nationalHolidays as $holiday) {
            Holiday::create(array_merge($holiday, [
                'company_id' => null, // Libur nasional
                'is_recurring' => in_array($holiday['date'], ['2024-01-01', '2024-05-01', '2024-06-01', '2024-08-17', '2024-12-25']),
            ]));
        }

        // Company-specific holidays
        $companyHolidays = [
            // PT Teknologi Maju Bersama
            [
                'company_id' => 1,
                'name' => 'Company Anniversary',
                'date' => '2024-03-15',
                'type' => 'company',
                'description' => '5th Anniversary PT Teknologi Maju Bersama',
            ],
            [
                'company_id' => 1,
                'name' => 'Team Building Day',
                'date' => '2024-07-20',
                'type' => 'company',
                'description' => 'Annual team building event',
            ],

            // CV Kreatif Digital Solutions
            [
                'company_id' => 2,
                'name' => 'Founder Birthday',
                'date' => '2024-05-10',
                'type' => 'company',
                'description' => 'Hari ulang tahun pendiri perusahaan',
            ],

            // Toko Berkah Jaya
            [
                'company_id' => 3,
                'name' => 'Store Anniversary',
                'date' => '2024-06-25',
                'type' => 'company',
                'description' => '10th Anniversary Toko Berkah Jaya',
            ],

            // PT Industri Manufaktur Nusantara
            [
                'company_id' => 4,
                'name' => 'Safety Day',
                'date' => '2024-04-28',
                'type' => 'company',
                'description' => 'National Occupational Safety and Health Day',
            ],
            [
                'company_id' => 4,
                'name' => 'Production Milestone Celebration',
                'date' => '2024-09-15',
                'type' => 'company',
                'description' => 'Celebrating 1 million units produced',
            ],
        ];

        foreach ($companyHolidays as $holiday) {
            Holiday::create($holiday);
        }
    }
}
