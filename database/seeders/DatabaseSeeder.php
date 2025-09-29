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
            // Core system data
            PackageSeeder::class,
            InstansiSeeder::class,
            UserSeeder::class,
            SubscriptionSeeder::class,
            SupportRequestSeeder::class,
            SettingsSeeder::class,

            // Existing seeders (keeping for compatibility)
            AttendancePolicySeeder::class,
            WorkShiftSeeder::class,
            EmployeeScheduleSeeder::class,
            HolidaySeeder::class,
            AttendanceSeeder::class,
            LeaveSeeder::class,
            PayrollSeeder::class,
            AllowanceDeductionSeeder::class,
            QRCodeSeeder::class,
        ]);
    }
}
