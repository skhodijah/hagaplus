<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Admin\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employee = $user->employee;
        $attendancePolicy = null;

        if ($employee) {
            $attendancePolicy = $employee->attendancePolicy;
            
            // If no specific policy, get default policy
            if (!$attendancePolicy) {
                $attendancePolicy = \App\Models\Admin\AttendancePolicy::where('is_default', true)->first();
            }
        }

        // Get today's attendance
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', today())
            ->first();

        // Get recent attendance records
        $recentAttendance = Attendance::where('user_id', $user->id)
            ->orderBy('attendance_date', 'desc')
            ->take(10)
            ->get();

        return view('employee.attendance.index', compact(
            'todayAttendance', 
            'recentAttendance', 
            'employee',
            'attendancePolicy'
        ));
    }
    
    /**
     * Show the attendance policy for the employee
     */
    public function showPolicy()
    {
        $user = Auth::user();
        $employee = $user->employee;
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Data karyawan tidak ditemukan'
            ], 404);
        }
        
        $attendancePolicy = $employee->attendancePolicy;
        
        // If no specific policy, get default policy
        if (!$attendancePolicy) {
            $attendancePolicy = \App\Models\Admin\AttendancePolicy::where('is_default', true)->first();
        }
        
        if (!$attendancePolicy) {
            return response()->json([
                'success' => false,
                'message' => 'Kebijakan kehadiran tidak ditemukan'
            ], 404);
        }
        
        // Format work days
        $daysMap = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];
        
        $workDays = collect($attendancePolicy->work_days ?? [])
            ->map(function($day) use ($daysMap) {
                return $daysMap[$day] ?? $day;
            })
            ->implode(', ');
        
        $policyData = [
            'name' => $attendancePolicy->name,
            'work_days' => $workDays,
            'start_time' => \Carbon\Carbon::parse($attendancePolicy->start_time)->format('H:i'),
            'end_time' => \Carbon\Carbon::parse($attendancePolicy->end_time)->format('H:i'),
            'late_tolerance' => $attendancePolicy->late_tolerance . ' menit',
            'early_checkout_tolerance' => $attendancePolicy->early_checkout_tolerance . ' menit',
            'break_duration' => $attendancePolicy->break_duration . ' menit',
            'overtime_after' => $attendancePolicy->overtime_after_minutes . ' menit setelah jam kerja',
            'attendance_methods' => is_array($attendancePolicy->attendance_methods) 
                ? implode(', ', $attendancePolicy->attendance_methods)
                : $attendancePolicy->attendance_methods,
            'auto_checkout' => $attendancePolicy->auto_checkout ? 'Aktif' : 'Tidak Aktif',
            'auto_checkout_time' => $attendancePolicy->auto_checkout_time 
                ? \Carbon\Carbon::parse($attendancePolicy->auto_checkout_time)->format('H:i')
                : '-',
        ];
        
        return response()->json([
            'success' => true,
            'data' => $policyData
        ]);
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'selfie' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        $user = Auth::user();

        // Check if already checked in today
        $existingAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', today())
            ->first();

        if ($existingAttendance && $existingAttendance->check_in_time) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah check in hari ini'
            ], 400);
        }

        try {
            // Store selfie photo
            $selfiePath = $request->file('selfie')->store('attendance-selfies', 'public');

            $checkInTime = now();
            $lateMinutes = 0;
            $attendancePolicy = null;

            // Get employee's attendance policy
            if ($user->employee && $user->employee->attendancePolicy) {
                $attendancePolicy = $user->employee->attendancePolicy;
            } else {
                // Fallback to default policy if no specific policy is set
                $attendancePolicy = \App\Models\Admin\AttendancePolicy::where('is_default', true)->first();
            }

            // Calculate late minutes if policy exists
            if ($attendancePolicy) {
                $workStartTime = Carbon::parse($attendancePolicy->start_time);
                $lateTolerance = $attendancePolicy->late_tolerance ?? 15;

                $graceTime = $workStartTime->copy()->addMinutes($lateTolerance);

                if ($checkInTime->greaterThan($workStartTime)) {
                    $lateMinutes = $workStartTime->diffInMinutes($checkInTime);
                    
                    // Only count as late if after grace period
                    if ($checkInTime->greaterThan($graceTime)) {
                        $lateMinutes = $workStartTime->diffInMinutes($checkInTime);
                    } else {
                        $lateMinutes = 0; // Within grace period
                    }
                }
            }

            if ($existingAttendance) {
                // Update existing record
                $existingAttendance->update([
                    'check_in_time' => $checkInTime,
                    'check_in_method' => 'selfie',
                    'check_in_photo' => $selfiePath,
                    'late_minutes' => $lateMinutes,
                    'status' => $lateMinutes > 0 ? 'late' : 'present',
                ]);

                $attendance = $existingAttendance;
            } else {
                // Create new attendance record
                $attendance = Attendance::create([
                    'user_id' => $user->id,
                    'branch_id' => $user->employee->branch_id ?? 1,
                    'attendance_date' => today(),
                    'check_in_time' => $checkInTime,
                    'check_in_method' => 'selfie',
                    'check_in_photo' => $selfiePath,
                    'late_minutes' => $lateMinutes,
                    'status' => $lateMinutes > 0 ? 'late' : 'present',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Check in berhasil!',
                'data' => [
                    'check_in_time' => $attendance->check_in_time->format('H:i:s'),
                    'late_minutes' => $lateMinutes,
                    'status' => $attendance->status
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat check in'
            ], 500);
        }
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'selfie' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $user = Auth::user();

        // Find today's attendance
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', today())
            ->whereNotNull('check_in_time')
            ->first();

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum check in hari ini'
            ], 400);
        }

        if ($attendance->check_out_time) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah check out hari ini'
            ], 400);
        }

        try {
            // Store selfie photo
            $selfiePath = $request->file('selfie')->store('attendance-selfies', 'public');

            // Calculate work duration
            $checkInTime = Carbon::parse($attendance->check_in_time);
            $checkOutTime = now();
            $workDuration = $checkInTime->diffInMinutes($checkOutTime);

            // Get employee schedule and attendance policy for early checkout calculation
            $employeeSchedule = \DB::table('employee_schedules')
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->where('effective_date', '<=', today())
                ->orderBy('effective_date', 'desc')
                ->first();

            $earlyCheckoutMinutes = 0;

            // Calculate early checkout minutes if schedule and policy exist
            if ($employeeSchedule) {
                $attendancePolicy = \DB::table('attendance_policies')
                    ->where('id', $employeeSchedule->policy_id)
                    ->first();

                if ($attendancePolicy) {
                    $workEndTime = Carbon::createFromTimeString($attendancePolicy->end_time);
                    $earlyCheckoutTolerance = $attendancePolicy->early_checkout_tolerance ?? 15;

                    $graceTime = $workEndTime->copy()->subMinutes($earlyCheckoutTolerance);

                    if ($checkOutTime->lessThan($graceTime)) {
                        $earlyCheckoutMinutes = $workEndTime->diffInMinutes($checkOutTime, false);
                        $earlyCheckoutMinutes = max(0, $earlyCheckoutMinutes); // Ensure non-negative
                    }
                }
            }

            $attendance->update([
                'check_out_time' => $checkOutTime,
                'check_out_method' => 'selfie',
                'check_out_photo' => $selfiePath,
                'work_duration' => $workDuration,
                'early_checkout_minutes' => $earlyCheckoutMinutes,
                'status' => $attendance->late_minutes > 0 ? 'late' : 'present', // Keep existing status or update if needed
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Check out berhasil!',
                'data' => [
                    'check_out_time' => $attendance->check_out_time->format('H:i:s'),
                    'work_duration' => $workDuration,
                    'early_checkout_minutes' => $earlyCheckoutMinutes,
                    'status' => $attendance->status
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat check out'
            ], 500);
        }
    }

    public function processScan(Request $request)
    {
        // Keep existing QR scan functionality if needed
        return response()->json(['message' => 'QR scan not implemented yet']);
    }
}
