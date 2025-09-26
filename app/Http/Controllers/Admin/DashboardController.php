<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Employee;
use App\Models\Admin\Attendance;
use App\Models\Admin\Branch;
use App\Models\Admin\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $instansiId = Auth::user()->instansi_id;
        $today = Carbon::today();
        $currentMonth = Carbon::now()->format('Y-m');

        // === BASIC METRICS ===
        $totalEmployees = Employee::where('instansi_id', $instansiId)->count();
        $activeEmployees = Employee::where('instansi_id', $instansiId)->where('status', 'active')->count();
        $totalBranches = Branch::where('company_id', $instansiId)->count();
        $activeBranches = Branch::where('company_id', $instansiId)->where('is_active', true)->count();

        // === TODAY'S ATTENDANCE ===
        $presentToday = Attendance::whereHas('user', function($query) use ($instansiId) {
            $query->where('instansi_id', $instansiId);
        })
        ->where('attendance_date', $today->toDateString())
        ->where('status', 'present')
        ->count();

        $lateToday = Attendance::whereHas('user', function($query) use ($instansiId) {
            $query->where('instansi_id', $instansiId);
        })
        ->where('attendance_date', $today->toDateString())
        ->where('status', 'late')
        ->count();

        $absentToday = Attendance::whereHas('user', function($query) use ($instansiId) {
            $query->where('instansi_id', $instansiId);
        })
        ->where('attendance_date', $today->toDateString())
        ->where('status', 'absent')
        ->count();

        $attendanceRateToday = $totalEmployees > 0 ? round((($presentToday + $lateToday) / $totalEmployees) * 100, 1) : 0;

        // === MONTHLY ATTENDANCE TREND ===
        $monthlyAttendance = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $present = Attendance::whereHas('user', function($query) use ($instansiId) {
                $query->where('instansi_id', $instansiId);
            })
            ->where('attendance_date', $date)
            ->whereIn('status', ['present', 'late'])
            ->count();

            $monthlyAttendance[] = [
                'date' => Carbon::parse($date)->format('M d'),
                'present' => $present,
                'rate' => $totalEmployees > 0 ? round(($present / $totalEmployees) * 100, 1) : 0
            ];
        }

        // === PAYROLL METRICS ===
        $totalPayroll = Payroll::whereHas('user', function($query) use ($instansiId) {
            $query->where('instansi_id', $instansiId);
        })->count();

        $pendingPayroll = Payroll::whereHas('user', function($query) use ($instansiId) {
            $query->where('instansi_id', $instansiId);
        })
        ->where('payment_status', 'draft')
        ->count();

        $paidPayroll = Payroll::whereHas('user', function($query) use ($instansiId) {
            $query->where('instansi_id', $instansiId);
        })
        ->where('payment_status', 'paid')
        ->count();

        $processedPayroll = Payroll::whereHas('user', function($query) use ($instansiId) {
            $query->where('instansi_id', $instansiId);
        })
        ->where('payment_status', 'processed')
        ->count();

        // Total salary amounts
        $totalSalaryAmount = Payroll::whereHas('user', function($query) use ($instansiId) {
            $query->where('instansi_id', $instansiId);
        })
        ->where('payment_status', 'paid')
        ->sum('net_salary');

        // === DEPARTMENT DISTRIBUTION ===
        $departmentStats = Employee::where('instansi_id', $instansiId)
            ->select('department', DB::raw('count(*) as count'))
            ->whereNotNull('department')
            ->groupBy('department')
            ->orderBy('count', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'department' => $item->department ?: 'Unassigned',
                    'count' => $item->count,
                    'percentage' => 0 // Will be calculated in view
                ];
            });

        // === BRANCH PERFORMANCE ===
        $branchPerformance = Branch::where('company_id', $instansiId)
            ->withCount(['employees', 'attendances' => function($query) use ($today) {
                $query->where('attendance_date', $today->toDateString())
                      ->whereIn('status', ['present', 'late']);
            }])
            ->get()
            ->map(function($branch) {
                $attendanceRate = $branch->employees_count > 0
                    ? round(($branch->attendances_count / $branch->employees_count) * 100, 1)
                    : 0;

                return [
                    'name' => $branch->name,
                    'employees' => $branch->employees_count,
                    'present_today' => $branch->attendances_count,
                    'attendance_rate' => $attendanceRate
                ];
            });

        // === RECENT ACTIVITIES ===
        $recentActivities = Attendance::with(['user', 'branch'])
            ->whereHas('user', function($query) use ($instansiId) {
                $query->where('instansi_id', $instansiId);
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($attendance) {
                return [
                    'employee' => $attendance->user->name,
                    'action' => $this->getAttendanceAction($attendance),
                    'time' => $attendance->created_at->diffForHumans(),
                    'status' => $attendance->status
                ];
            });

        // === QUICK STATS ===
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        $thisMonthAttendance = Attendance::whereHas('user', function($query) use ($instansiId) {
            $query->where('instansi_id', $instansiId);
        })
        ->where('attendance_date', '>=', $thisMonth)
        ->whereIn('status', ['present', 'late'])
        ->count();

        $lastMonthAttendance = Attendance::whereHas('user', function($query) use ($instansiId) {
            $query->where('instansi_id', $instansiId);
        })
        ->whereBetween('attendance_date', [$lastMonth, $lastMonth->copy()->endOfMonth()])
        ->whereIn('status', ['present', 'late'])
        ->count();

        $attendanceChange = $lastMonthAttendance > 0
            ? round((($thisMonthAttendance - $lastMonthAttendance) / $lastMonthAttendance) * 100, 1)
            : 0;

        return view('admin.dashboard.index', compact(
            // Basic metrics
            'totalEmployees', 'activeEmployees', 'totalBranches', 'activeBranches',

            // Today's attendance
            'presentToday', 'lateToday', 'absentToday', 'attendanceRateToday',

            // Charts data
            'monthlyAttendance',

            // Payroll metrics
            'totalPayroll', 'pendingPayroll', 'paidPayroll', 'processedPayroll', 'totalSalaryAmount',

            // Department and branch data
            'departmentStats', 'branchPerformance',

            // Recent activities
            'recentActivities',

            // Trends
            'attendanceChange'
        ));
    }

    private function getAttendanceAction($attendance)
    {
        $action = '';

        if ($attendance->check_in_time && $attendance->check_out_time) {
            $action = 'Completed full day';
        } elseif ($attendance->check_in_time) {
            $action = 'Checked in';
        } elseif ($attendance->check_out_time) {
            $action = 'Checked out';
        } else {
            $action = 'Marked ' . $attendance->status;
        }

        return $action;
    }
}
