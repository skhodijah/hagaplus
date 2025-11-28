<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Admin\Employee;
use App\Models\Admin\Attendance;
use App\Models\Admin\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->with(['instansi', 'branch'])->first();

        if (!$employee) {
            return view('employee.dashboard.index', [
                'error' => 'Employee profile not found. Please contact your administrator.'
            ]);
        }

        // Today's attendance status
        $today = Carbon::today();
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', $today)
            ->first();

        $todayStatus = $this->getAttendanceStatusText($todayAttendance);

        // Get selected month from query parameter or use current month
        $selectedMonth = $request->input('month');
        if ($selectedMonth) {
            try {
                $selectedDate = Carbon::createFromFormat('Y-m', $selectedMonth);
                $currentMonth = $selectedDate->month;
                $currentYear = $selectedDate->year;
            } catch (\Exception $e) {
                $currentMonth = Carbon::now()->month;
                $currentYear = Carbon::now()->year;
            }
        } else {
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
        }

        // Monthly working hours (work_duration is in minutes, convert to hours)
        $monthlyMinutes = Attendance::where('user_id', $user->id)
            ->whereMonth('attendance_date', $currentMonth)
            ->whereYear('attendance_date', $currentYear)
            ->whereNotNull('check_out_time')
            ->sum('work_duration') ?? 0;
        $monthlyHours = round($monthlyMinutes / 60, 1);

        // Last payroll
        $lastPayroll = Payroll::where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->latest('period_year')
            ->latest('period_month')
            ->first();

        // Attendance statistics for current month
        $attendanceStats = $this->getAttendanceStats($user->id, $currentMonth, $currentYear);

        // Recent attendance records for selected month
        $startOfMonth = Carbon::create($currentYear, $currentMonth, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        $recentAttendance = Attendance::where('user_id', $user->id)
            ->whereBetween('attendance_date', [$startOfMonth, $endOfMonth])
            ->orderBy('attendance_date', 'desc')
            ->get();

        // Get approved leaves for the selected month
        $leaves = \App\Models\Admin\Leave::where('user_id', $user->id)
            ->where('status', 'approved')
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                    ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth])
                    ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                        $q->where('start_date', '<', $startOfMonth)
                            ->where('end_date', '>', $endOfMonth);
                    });
            })
            ->get();

        // Check if today is a leave day
        $isLeaveToday = false;
        $todayLeave = $leaves->filter(function ($leave) use ($today) {
            return $today->between($leave->start_date, $leave->end_date);
        })->first();

        if ($todayLeave) {
            $todayStatus = 'Sedang Cuti';
        }

        // Merge leaves into recentAttendance for calendar display
        foreach ($leaves as $leave) {
            $startDate = $leave->start_date < $startOfMonth ? $startOfMonth : $leave->start_date;
            $endDate = $leave->end_date > $endOfMonth ? $endOfMonth : $leave->end_date;

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                // Check if attendance already exists
                $exists = $recentAttendance->contains(function ($att) use ($date) {
                    return $att->attendance_date->isSameDay($date);
                });

                if (!$exists) {
                    $virtualAttendance = new Attendance([
                        'user_id' => $user->id,
                        'attendance_date' => $date->copy(),
                        'status' => 'leave',
                    ]);
                    $recentAttendance->push($virtualAttendance);
                }
            }
        }
        
        $recentAttendance = $recentAttendance->sortByDesc('attendance_date');

        // Calendar data for selected month
        $calendarData = $this->getCalendarData($user->id, $currentMonth, $currentYear);

        // Branch data for geolocation tracking
        $branchData = [
            'name' => $employee->branch->name ?? 'Kantor',
            'latitude' => $employee->branch->latitude ?? null,
            'longitude' => $employee->branch->longitude ?? null,
            'radius' => $employee->branch->radius ?? 100,
        ];

        // Calculate work duration from hire date
        $workDuration = null;
        if ($employee->hire_date) {
            $hireDate = Carbon::parse($employee->hire_date);
            $now = Carbon::now();
            $diff = $hireDate->diff($now);
            
            $workDuration = [
                'years' => $diff->y,
                'months' => $diff->m,
                'days' => $diff->d,
                'total_days' => $hireDate->diffInDays($now),
            ];
        }

        // Get effective policy for the employee
        $policy = $employee->effective_policy;
        $workDays = [];
        
        if ($policy && $policy->work_days) {
            // work_days is a JSON array, decode if needed
            $workDays = is_array($policy->work_days) ? $policy->work_days : json_decode($policy->work_days, true);
            // Ensure it's an array
            $workDays = $workDays ?? [];
        }

        return view('employee.dashboard.index', [
            'employee' => $employee,
            'user' => $user,
            'todayStatus' => $todayStatus,
            'monthlyHours' => $monthlyHours,
            'lastPayroll' => $lastPayroll ? $lastPayroll->gaji_bersih : 0,
            'attendanceStats' => $attendanceStats,
            'recentAttendance' => $recentAttendance,
            'calendarData' => $calendarData,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
            'branchData' => $branchData,
            'workDuration' => $workDuration,
            'policy' => $policy,
            'workDays' => $workDays,
        ]);
    }

    private function getAttendanceStatusText($attendance)
    {
        if (!$attendance) {
            return 'Belum Check In';
        }

        if ($attendance->check_in_time && !$attendance->check_out_time) {
            return 'Sedang Bekerja';
        }

        if ($attendance->check_in_time && $attendance->check_out_time) {
            return 'Sudah Check Out';
        }

        return 'Belum Check In';
    }

    private function getAttendanceStats($userId, $month, $year)
    {
        $totalDays = Attendance::where('user_id', $userId)
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->count();

        $presentDays = Attendance::where('user_id', $userId)
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->whereNotNull('check_in_time')
            ->count();

        $lateDays = Attendance::where('user_id', $userId)
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->where('late_minutes', '>', 0)
            ->count();

        $totalWorkingMinutes = Attendance::where('user_id', $userId)
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->whereNotNull('check_out_time')
            ->sum('work_duration') ?? 0;

        $totalWorkingHours = round($totalWorkingMinutes / 60, 1);

        // Calculate working days in the month (excluding weekends)
        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        $workingDays = 0;

        $currentDate = $startOfMonth->copy();
        while ($currentDate <= $endOfMonth) {
            if (!$currentDate->isWeekend()) {
                $workingDays++;
            }
            $currentDate->addDay();
        }

        $absentDays = max(0, $workingDays - $presentDays);

        return [
            'total_days' => $totalDays,
            'present_days' => $presentDays,
            'late_days' => $lateDays,
            'total_hours' => $totalWorkingHours,
            'absent_days' => $absentDays,
            'working_days' => $workingDays,
        ];
    }

    private function getCalendarData($userId, $month, $year)
    {
        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $attendanceRecords = Attendance::where('user_id', $userId)
            ->whereBetween('attendance_date', [$startOfMonth, $endOfMonth])
            ->get()
            ->keyBy(function ($item) {
                return $item->attendance_date->format('Y-m-d');
            });

        $calendarData = [];
        $currentDate = $startOfMonth->copy();

        while ($currentDate <= $endOfMonth) {
            $dateKey = $currentDate->format('Y-m-d');
            $attendance = $attendanceRecords->get($dateKey);

            $calendarData[] = [
                'date' => $currentDate->format('Y-m-d'),
                'day' => $currentDate->day,
                'status' => $this->getCalendarStatus($attendance),
                'is_today' => $currentDate->isToday(),
                'is_weekend' => $currentDate->isWeekend(),
            ];

            $currentDate->addDay();
        }

        return $calendarData;
    }

    private function getCalendarStatus($attendance)
    {
        if (!$attendance) {
            return 'absent';
        }

        if ($attendance->check_in_time && $attendance->check_out_time) {
            return $attendance->late_minutes > 0 ? 'late' : 'present';
        }

        if ($attendance->check_in_time && !$attendance->check_out_time) {
            return 'checked_in';
        }

        return 'absent';
    }
}
