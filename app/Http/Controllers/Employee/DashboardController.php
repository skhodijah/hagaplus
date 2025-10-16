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
    public function index()
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

        // Monthly working hours (work_duration is in minutes, convert to hours)
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
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

        // Recent attendance records (last 7 days)
        $recentAttendance = Attendance::where('user_id', $user->id)
            ->where('attendance_date', '>=', Carbon::now()->subDays(7))
            ->orderBy('attendance_date', 'desc')
            ->get();

        // Calendar data for current month
        $calendarData = $this->getCalendarData($user->id, $currentMonth, $currentYear);

        return view('employee.dashboard.index', [
            'employee' => $employee,
            'user' => $user,
            'todayStatus' => $todayStatus,
            'monthlyHours' => $monthlyHours,
            'lastPayroll' => $lastPayroll ? $lastPayroll->net_salary : 0,
            'attendanceStats' => $attendanceStats,
            'recentAttendance' => $recentAttendance,
            'calendarData' => $calendarData,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
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
