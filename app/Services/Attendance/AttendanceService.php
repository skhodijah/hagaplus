<?php

namespace App\Services\Attendance;

use App\Models\Admin\Attendance;
use App\Models\Admin\Employee;

class AttendanceService
{
    public function checkIn(Employee $employee, $data = [])
    {
        // Check if employee already checked in today
        $existingAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('check_in', today())
            ->whereNull('check_out')
            ->first();

        if ($existingAttendance) {
            throw new \Exception('Employee has already checked in today.');
        }

        return Attendance::create(array_merge([
            'employee_id' => $employee->id,
            'check_in' => now(),
            'status' => 'present',
        ], $data));
    }

    public function checkOut(Employee $employee, $data = [])
    {
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('check_in', today())
            ->whereNull('check_out')
            ->first();

        if (!$attendance) {
            throw new \Exception('No check-in record found for today.');
        }

        $attendance->update(array_merge([
            'check_out' => now(),
        ], $data));

        return $attendance;
    }

    public function getAttendanceReport($employeeId, $startDate, $endDate)
    {
        return Attendance::where('employee_id', $employeeId)
            ->whereBetween('check_in', [$startDate, $endDate])
            ->orderBy('check_in', 'desc')
            ->get();
    }
}
