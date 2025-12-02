<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RealisticEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
        $instansiId = 1;

        // Ensure Instansi exists
        $instansi = \App\Models\SuperAdmin\Instansi::find($instansiId);
        if (!$instansi) {
            $this->command->error("Instansi with ID $instansiId not found. Please create it first.");
            return;
        }

        // Get or Create Instansi Roles
        $roles = [
            'HRD' => \App\Models\Admin\InstansiRole::firstOrCreate(
                ['name' => 'HRD', 'instansi_id' => $instansiId],
                ['description' => 'Human Resources Department']
            ),
            'User' => \App\Models\Admin\InstansiRole::firstOrCreate(
                ['name' => 'User', 'instansi_id' => $instansiId],
                ['description' => 'Regular User / Manager']
            ),
            'Employee' => \App\Models\Admin\InstansiRole::firstOrCreate(
                ['name' => 'Employee', 'instansi_id' => $instansiId],
                ['description' => 'Regular Employee']
            ),
        ];

        // Create 3 Branches
        $branches = [];
        $branchLocations = [
            ['name' => 'Kantor Pusat Jakarta', 'lat' => -6.2088, 'lng' => 106.8456],
            ['name' => 'Cabang Bandung', 'lat' => -6.9175, 'lng' => 107.6191],
            ['name' => 'Cabang Surabaya', 'lat' => -7.2575, 'lng' => 112.7521],
        ];

        foreach ($branchLocations as $loc) {
            $branches[] = \App\Models\Admin\Branch::firstOrCreate(
                ['name' => $loc['name'], 'company_id' => $instansiId],
                [
                    'address' => $faker->address,
                    'latitude' => $loc['lat'],
                    'longitude' => $loc['lng'],
                    'radius' => 100,
                ]
            );
        }

        // Create Divisions & Departments (Simplified)
        $division = \App\Models\Admin\Division::firstOrCreate(
            ['name' => 'Operasional', 'instansi_id' => $instansiId],
            ['code' => 'OPS']
        );
        $department = \App\Models\Admin\Department::firstOrCreate(
            ['name' => 'Umum', 'instansi_id' => $instansiId, 'division_id' => $division->id]
        );
        $position = \App\Models\Admin\Position::firstOrCreate(
            ['name' => 'Staff', 'instansi_id' => $instansiId, 'department_id' => $department->id, 'division_id' => $division->id]
        );

        // Create Employees for each Branch
        foreach ($branches as $index => $branch) {
            $this->command->info("Creating employees for branch: {$branch->name}");

            for ($i = 0; $i < 5; $i++) {
                // Determine Role: 1 HRD per branch (if index 0), 1 User (Manager), 3 Employees
                // Let's mix it up.
                // First person in branch is 'User' (Manager-like), others 'Employee'.
                // Except in first branch, first person is 'HRD'.
                
                if ($index == 0 && $i == 0) {
                    $roleName = 'HRD';
                } elseif ($i == 0) {
                    $roleName = 'User';
                } else {
                    $roleName = 'Employee';
                }
                
                $role = $roles[$roleName];

                // Create User
                $gender = $faker->randomElement(['male', 'female']);
                $firstName = $faker->firstName($gender);
                $lastName = $faker->lastName;
                $name = "$firstName $lastName";
                $email = strtolower($firstName . '.' . $lastName . rand(1, 99) . '@example.com');

                $user = \App\Models\Core\User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => bcrypt('password'),
                    'system_role_id' => 2, // Admin/Employee role in system (assuming 1 is SuperAdmin)
                    'instansi_id' => $instansiId,
                ]);

                // Create Employee Profile
                $employee = \App\Models\Admin\Employee::create([
                    'user_id' => $user->id,
                    'instansi_id' => $instansiId,
                    'branch_id' => $branch->id,
                    'instansi_role_id' => $role->id,
                    'division_id' => $division->id,
                    'department_id' => $department->id,
                    'position_id' => $position->id,
                    'employee_id' => 'EMP' . $branch->id . str_pad($i + 1, 3, '0', STR_PAD_LEFT) . rand(100, 999),
                    'nik' => $faker->nik,
                    'status' => 'active',
                    'hire_date' => $faker->dateTimeBetween('-2 years', '-1 month'),
                    'gender' => $gender, // 'male' or 'female'
                    'phone' => $faker->phoneNumber,
                    'address' => $faker->address,
                    'salary' => $faker->numberBetween(5000000, 15000000),
                ]);

                // Generate Attendance for last 30 days
                $this->generateAttendance($user, $branch);
            }
        }
    }

    private function generateAttendance($user, $branch)
    {
        $startDate = \Carbon\Carbon::now()->subDays(30);
        $endDate = \Carbon\Carbon::now();
        $faker = \Faker\Factory::create();

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            if ($date->isWeekend()) continue;

            // Randomize attendance scenarios
            $rand = rand(1, 100);
            
            if ($rand <= 5) {
                // Absent (5%)
                continue;
            } elseif ($rand <= 10) {
                // Leave (5%) - Simplified, just skip creating attendance or mark as leave if system supports
                // For now, let's skip to simulate absence/leave without record
                continue;
            }

            // Present
            $checkInTime = $date->copy()->setTime(8, 0, 0);
            
            // Late logic (20% chance)
            $isLate = rand(1, 100) <= 20;
            if ($isLate) {
                $checkInTime->addMinutes(rand(1, 60));
            } else {
                // Arrive slightly early or on time
                $checkInTime->subMinutes(rand(0, 15));
            }

            // Check out time
            $checkOutTime = $date->copy()->setTime(17, 0, 0);
            // Leave early logic (10% chance)
            $isEarlyLeave = rand(1, 100) <= 10;
            if ($isEarlyLeave) {
                $checkOutTime->subMinutes(rand(1, 120));
            } else {
                // Overtime or on time
                $checkOutTime->addMinutes(rand(0, 60));
            }

            $workDuration = $checkInTime->diffInMinutes($checkOutTime);
            $lateMinutes = $isLate ? $checkInTime->diffInMinutes($date->copy()->setTime(8, 0, 0)) : 0; // Simplified late calc

            \App\Models\Admin\Attendance::create([
                'user_id' => $user->id,
                'branch_id' => $branch->id,
                'attendance_date' => $date->format('Y-m-d'),
                'check_in_time' => $checkInTime,
                'check_out_time' => $checkOutTime,
                'status' => $isLate ? 'late' : 'present',
                'work_duration' => $workDuration,
                'late_minutes' => $lateMinutes,
                'check_in_photo' => 'dummy/selfie.jpg', // Placeholder
            ]);
        }
    }
}
