<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Leave;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;

class LeaveSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $employees = User::where('role', 'employee')->take(20)->get(); // Sample 20 employees
        $admins = User::where('role', 'admin')->get();

        foreach ($employees as $employee) {
            // Generate 1-3 leave requests per employee
            $leaveCount = $faker->numberBetween(1, 3);

            for ($i = 0; $i < $leaveCount; $i++) {
                $startDate = $faker->dateTimeBetween('-6 months', '+1 month');
                $daysCount = $faker->numberBetween(1, 7);
                $endDate = Carbon::parse($startDate)->addDays($daysCount - 1);

                $leaveTypes = ['annual', 'sick', 'emergency', 'other'];
                $leaveType = $faker->randomElement($leaveTypes);

                $reasons = [
                    'annual' => 'Liburan keluarga',
                    'sick' => 'Sakit demam dan flu',
                    'emergency' => 'Keperluan keluarga mendesak',
                    'other' => 'Acara pernikahan saudara',
                ];

                $statuses = ['approved', 'approved', 'approved', 'pending', 'rejected']; // 60% approved
                $status = $faker->randomElement($statuses);

                $admin = $admins->where('company_id', $employee->company_id)->first();

                Leave::create([
                    'user_id' => $employee->id,
                    'leave_type' => $leaveType,
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                    'days_count' => $daysCount,
                    'reason' => $reasons[$leaveType] ?? 'Keperluan pribadi',
                    'status' => $status,
                    'approved_by' => $status !== 'pending' ? $admin->id : null,
                    'approved_at' => $status !== 'pending' ? $faker->dateTimeBetween($startDate, 'now') : null,
                    'rejection_reason' => $status === 'rejected' ? 'Periode peak season, tidak bisa mengambil cuti' : null,
                ]);
            }
        }
    }
}
