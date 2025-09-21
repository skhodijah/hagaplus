<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payroll;
use App\Models\User;
use Carbon\Carbon;

class PayrollSeeder extends Seeder
{
    public function run()
    {
        $employees = User::where('role', 'employee')->get();
        $admins = User::where('role', 'admin')->get();

        // Generate payroll for last 3 months
        $months = [
            ['year' => 2024, 'month' => 7],
            ['year' => 2024, 'month' => 8],
            ['year' => 2024, 'month' => 9],
        ];

        foreach ($employees as $employee) {
            foreach ($months as $period) {
                $admin = $admins->where('company_id', $employee->company_id)->first();

                // Calculate allowances (10-20% of basic salary)
                $transportAllowance = $employee->salary * 0.10;
                $mealAllowance = $employee->salary * 0.08;
                $performanceBonus = rand(0, 1) ? $employee->salary * 0.15 : 0;

                $totalAllowances = $transportAllowance + $mealAllowance + $performanceBonus;

                // Calculate deductions
                $tax = $employee->salary * 0.05; // 5% tax
                $bpjs = $employee->salary * 0.04; // 4% BPJS
                $latePenalty = rand(0, 1) ? 50000 : 0; // Random late penalty

                $totalDeductions = $tax + $bpjs + $latePenalty;

                // Overtime calculation (random)
                $overtimeHours = rand(0, 20);
                $overtimeRate = $employee->salary / 173; // Per hour rate
                $overtimeAmount = $overtimeHours * $overtimeRate * 1.5;

                $totalGross = $employee->salary + $totalAllowances + $overtimeAmount;
                $netSalary = $totalGross - $totalDeductions;

                Payroll::create([
                    'user_id' => $employee->id,
                    'period_year' => $period['year'],
                    'period_month' => $period['month'],
                    'basic_salary' => $employee->salary,
                    'allowances' => json_encode([
                        'transport' => $transportAllowance,
                        'meal' => $mealAllowance,
                        'performance' => $performanceBonus,
                    ]),
                    'deductions' => json_encode([
                        'tax' => $tax,
                        'bpjs' => $bpjs,
                        'late_penalty' => $latePenalty,
                    ]),
                    'overtime_amount' => $overtimeAmount,
                    'total_gross' => $totalGross,
                    'total_deductions' => $totalDeductions,
                    'net_salary' => $netSalary,
                    'payment_date' => Carbon::create($period['year'], $period['month'])->endOfMonth()->addDays(5),
                    'payment_status' => 'paid',
                    'created_by' => $admin->id,
                ]);
            }
        }
    }
}
