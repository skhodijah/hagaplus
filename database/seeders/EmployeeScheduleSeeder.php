<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmployeeSchedule;
use App\Models\User;

class EmployeeScheduleSeeder extends Seeder
{
    public function run()
    {
        // Assign default policies to all employees
        $employees = User::where('role', 'employee')->get();

        foreach ($employees as $employee) {
            $policyId = null;

            // Determine policy based on company
            switch ($employee->company_id) {
                case 1: // PT Teknologi Maju
                    $policyId = 1; // Kebijakan Standar Office
                    break;
                case 2: // CV Kreatif Digital
                    $policyId = 3; // Creative Team Schedule
                    break;
                case 3: // Toko Berkah Jaya
                    $policyId = 4; // Retail Store Hours
                    break;
                case 4: // PT Industri Manufaktur
                    $policyId = 5; // Shift Pagi (default)
                    break;
            }

            if ($policyId) {
                EmployeeSchedule::create([
                    'user_id' => $employee->id,
                    'policy_id' => $policyId,
                    'effective_date' => $employee->hire_date,
                    'is_active' => true,
                ]);
            }
        }
    }
}
