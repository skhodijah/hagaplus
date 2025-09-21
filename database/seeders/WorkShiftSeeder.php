<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkShift;

class WorkShiftSeeder extends Seeder
{
    public function run()
    {
        $shifts = [
            // Company 1: Office shifts
            [
                'company_id' => 1,
                'name' => 'Regular Office Hours',
                'start_time' => '09:00:00',
                'end_time' => '18:00:00',
                'break_duration' => 60,
                'color' => '#3b82f6',
            ],
            [
                'company_id' => 1,
                'name' => 'Early Bird',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'break_duration' => 60,
                'color' => '#10b981',
            ],

            // Company 2: Creative flexible shifts
            [
                'company_id' => 2,
                'name' => 'Creative Hours',
                'start_time' => '10:00:00',
                'end_time' => '19:00:00',
                'break_duration' => 90,
                'color' => '#8b5cf6',
            ],

            // Company 3: Retail shifts
            [
                'company_id' => 3,
                'name' => 'Store Opening',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'break_duration' => 60,
                'color' => '#f59e0b',
            ],

            // Company 4: Manufacturing shifts
            [
                'company_id' => 4,
                'name' => 'Shift Pagi',
                'start_time' => '06:00:00',
                'end_time' => '14:00:00',
                'break_duration' => 30,
                'color' => '#ef4444',
            ],
            [
                'company_id' => 4,
                'name' => 'Shift Siang',
                'start_time' => '14:00:00',
                'end_time' => '22:00:00',
                'break_duration' => 30,
                'color' => '#f97316',
            ],
            [
                'company_id' => 4,
                'name' => 'Shift Malam',
                'start_time' => '22:00:00',
                'end_time' => '06:00:00',
                'break_duration' => 30,
                'color' => '#6366f1',
            ],
        ];

        foreach ($shifts as $shift) {
            WorkShift::create($shift);
        }
    }
}
