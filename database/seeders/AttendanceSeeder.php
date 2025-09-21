<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $employees = User::where('role', 'employee')->get();

        // Generate attendance for last 30 days
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now()->subDays(1);

        foreach ($employees as $employee) {
            $currentDate = $startDate->copy();

            while ($currentDate->lte($endDate)) {
                // Skip weekends for most companies (except company 3 & 4)
                if ($currentDate->isWeekend() && !in_array($employee->company_id, [3, 4])) {
                    $currentDate->addDay();
                    continue;
                }

                // 85% chance of attendance (realistic)
                if ($faker->numberBetween(1, 100) <= 85) {
                    $this->createAttendance($employee, $currentDate->copy(), $faker);
                }

                $currentDate->addDay();
            }
        }
    }

    private function createAttendance($employee, $date, $faker)
    {
        // Different schedules based on company
        $schedules = [
            1 => ['start' => '09:00', 'end' => '18:00'], // PT Teknologi Maju
            2 => ['start' => '10:00', 'end' => '19:00'], // CV Kreatif Digital
            3 => ['start' => '08:00', 'end' => '17:00'], // Toko Berkah Jaya
            4 => ['start' => '06:00', 'end' => '14:00'], // PT Industri Manufaktur
        ];

        $schedule = $schedules[$employee->company_id];

        // Add some randomness to arrival time (-30 to +45 minutes)
        $checkInVariation = $faker->numberBetween(-30, 45);
        $checkIn = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule['start'])
            ->addMinutes($checkInVariation);

        // Check out time (usually 8-9 hours later with some variation)
        $workHours = $faker->numberBetween(480, 540); // 8-9 hours in minutes
        $checkOut = $checkIn->copy()->addMinutes($workHours);

        // Calculate durations
        $workDuration = $checkIn->diffInMinutes($checkOut);
        $lateMinutes = max(0, $checkInVariation);

        // Determine status
        $status = 'present';
        if ($lateMinutes > 15) {
            $status = 'late';
        }

        // Random locations around branch coordinates
        $locations = [
            1 => ['lat' => -6.208763, 'lng' => 106.845599], // Jakarta
            2 => ['lat' => -6.238270, 'lng' => 107.001567], // Bekasi
            3 => ['lat' => -6.297524, 'lng' => 106.718124], // Tangerang
            4 => ['lat' => -6.921831, 'lng' => 107.607147], // Bandung
            5 => ['lat' => -6.895562, 'lng' => 107.613144], // Bandung Dago
            6 => ['lat' => -7.257472, 'lng' => 112.752088], // Surabaya
            7 => ['lat' => -6.296406, 'lng' => 107.154808], // Cikarang
            8 => ['lat' => -6.301206, 'lng' => 107.307809], // Karawang
        ];

        $branchLocation = $locations[$employee->branch_id];
        $checkInLocation = $branchLocation['lat'] + $faker->randomFloat(6, -0.001, 0.001) . ',' .
            $branchLocation['lng'] + $faker->randomFloat(6, -0.001, 0.001);

        Attendance::create([
            'user_id' => $employee->id,
            'branch_id' => $employee->branch_id,
            'attendance_date' => $date->format('Y-m-d'),
            'check_in_time' => $checkIn,
            'check_out_time' => $checkOut,
            'check_in_method' => $faker->randomElement(['qr', 'gps', 'face_id']),
            'check_out_method' => $faker->randomElement(['qr', 'gps', 'face_id']),
            'check_in_location' => $checkInLocation,
            'check_out_location' => $checkInLocation,
            'work_duration' => $workDuration,
            'break_duration' => 60,
            'overtime_duration' => max(0, $workDuration - 480), // Over 8 hours
            'late_minutes' => $lateMinutes,
            'early_checkout_minutes' => 0,
            'status' => $status,
            'notes' => $status === 'late' ? 'Terlambat karena macet' : null,
        ]);
    }
}
