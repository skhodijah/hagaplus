<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PackageSeeder::class,
            SuperAdminSeeder::class,
            CompanySeeder::class,
            BranchSeeder::class,
            UserSeeder::class,
            AttendancePolicySeeder::class,
            WorkShiftSeeder::class,
            EmployeeScheduleSeeder::class,
            HolidaySeeder::class,
            AttendanceSeeder::class,
            LeaveSeeder::class,
            PayrollSeeder::class,
            AllowanceDeductionSeeder::class,
            CompanyThemeSeeder::class,
            QRCodeSeeder::class,
            SystemSettingSeeder::class,
        ]);
    }
}
