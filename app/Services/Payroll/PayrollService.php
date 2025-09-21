<?php

namespace App\Services\Payroll;

use App\Models\Admin\Employee;
use App\Models\Admin\Payroll;
use App\Models\Admin\Attendance;

class PayrollService
{
    public function calculatePayroll(Employee $employee, $period)
    {
        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereBetween('check_in', [$period['start'], $period['end']])
            ->get();

        $totalHours = 0;
        $overtimeHours = 0;

        foreach ($attendances as $attendance) {
            if ($attendance->check_out) {
                $hours = $attendance->check_in->diffInHours($attendance->check_out);
                $totalHours += $hours;

                // Calculate overtime (assuming 8 hours is regular)
                if ($hours > 8) {
                    $overtimeHours += $hours - 8;
                }
            }
        }

        $regularPay = $totalHours * $employee->hourly_rate;
        $overtimePay = $overtimeHours * ($employee->hourly_rate * 1.5);
        $totalPay = $regularPay + $overtimePay;

        return [
            'total_hours' => $totalHours,
            'overtime_hours' => $overtimeHours,
            'regular_pay' => $regularPay,
            'overtime_pay' => $overtimePay,
            'total_pay' => $totalPay,
        ];
    }

    public function generatePayroll(Employee $employee, $period)
    {
        $calculation = $this->calculatePayroll($employee, $period);

        return Payroll::create([
            'employee_id' => $employee->id,
            'period_start' => $period['start'],
            'period_end' => $period['end'],
            'total_hours' => $calculation['total_hours'],
            'overtime_hours' => $calculation['overtime_hours'],
            'regular_pay' => $calculation['regular_pay'],
            'overtime_pay' => $calculation['overtime_pay'],
            'total_pay' => $calculation['total_pay'],
            'status' => 'pending',
        ]);
    }
}
