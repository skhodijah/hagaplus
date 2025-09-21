<?php

namespace App\Models\Trait;

trait HasPayroll
{
    public function payrolls()
    {
        return $this->morphMany(\App\Models\Admin\Payroll::class, 'payrollable');
    }

    public function calculateSalary($period)
    {
        // Calculate salary based on attendance and other factors
        $attendance = $this->attendances()
            ->whereBetween('check_in', [$period['start'], $period['end']])
            ->get();

        $totalHours = $attendance->sum(function ($record) {
            if ($record->check_out) {
                return $record->check_in->diffInHours($record->check_out);
            }
            return 0;
        });

        return $totalHours * $this->hourly_rate;
    }
}
