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
        $employee = Employee::where('user_id', $user->id)
            ->with(['instansi', 'branch', 'attendancePolicy', 'policy', 'division.policy'])
            ->first();

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

        // Get effective work days from policy hierarchy
        $workDays = [];
        if ($employee->effective_policy && $employee->effective_policy->work_days) {
            $workDays = $employee->effective_policy->work_days;
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

        // Fetch holidays for the month
        $holidays = \App\Models\Admin\Holiday::where(function($query) use ($startOfMonth, $endOfMonth) {
            $query->whereBetween('start_date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
                  ->orWhereBetween('end_date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
                  ->orWhere(function($q) use ($startOfMonth, $endOfMonth) {
                      $q->where('start_date', '<=', $startOfMonth->format('Y-m-d'))
                        ->where('end_date', '>=', $endOfMonth->format('Y-m-d'));
                  });
        })->get();

        // Create a map of holiday dates
        $holidayMap = [];
        foreach ($holidays as $holiday) {
            $period = \Carbon\CarbonPeriod::create($holiday->start_date, $holiday->end_date);
            foreach ($period as $date) {
                if ($date->month == $month && $date->year == $year) {
                    $holidayMap[$date->format('Y-m-d')] = $holiday->name;
                }
            }
        }

        $calendarData = [];
        $currentDate = $startOfMonth->copy();

        while ($currentDate <= $endOfMonth) {
            $dateKey = $currentDate->format('Y-m-d');
            $attendance = $attendanceRecords->get($dateKey);
            $isHoliday = isset($holidayMap[$dateKey]);
            $holidayName = $isHoliday ? $holidayMap[$dateKey] : null;

            $status = $this->getCalendarStatus($attendance);
            
            // If it's a holiday and no attendance record (or absent), mark as holiday
            if ($isHoliday && ($status === 'absent' || !$attendance)) {
                $status = 'holiday';
            }

            $calendarData[] = [
                'date' => $currentDate->format('Y-m-d'),
                'day' => $currentDate->day,
                'status' => $status,
                'is_today' => $currentDate->isToday(),
                'is_weekend' => $currentDate->isWeekend(),
                'is_holiday' => $isHoliday,
                'holiday_name' => $holidayName,
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
