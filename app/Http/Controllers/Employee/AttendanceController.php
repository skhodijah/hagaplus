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

        // Get today's attendance
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', today())
            ->first();

        // Get recent attendance records
        $recentAttendance = Attendance::where('user_id', $user->id)
            ->orderBy('attendance_date', 'desc')
            ->take(10)
            ->get();

        return view('employee.attendance.index', compact('todayAttendance', 'recentAttendance', 'employee'));
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'selfie' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
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

            if ($existingAttendance) {
                // Update existing record
                $existingAttendance->update([
                    'check_in_time' => now(),
                    'check_in_method' => 'selfie',
                    'check_in_photo' => $selfiePath,
                    'check_in_location' => $request->latitude && $request->longitude ?
                        $request->latitude . ',' . $request->longitude : null,
                    'status' => 'present',
                ]);

                $attendance = $existingAttendance;
            } else {
                // Create new attendance record
                $attendance = Attendance::create([
                    'user_id' => $user->id,
                    'branch_id' => $user->employee->branch_id ?? 1,
                    'attendance_date' => today(),
                    'check_in_time' => now(),
                    'check_in_method' => 'selfie',
                    'check_in_photo' => $selfiePath,
                    'check_in_location' => $request->latitude && $request->longitude ?
                        $request->latitude . ',' . $request->longitude : null,
                    'status' => 'present',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Check in berhasil!',
                'data' => [
                    'check_in_time' => $attendance->check_in_time->format('H:i:s'),
                    'status' => 'present'
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
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
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

            $attendance->update([
                'check_out_time' => $checkOutTime,
                'check_out_method' => 'selfie',
                'check_out_photo' => $selfiePath,
                'check_out_location' => $request->latitude && $request->longitude ?
                    $request->latitude . ',' . $request->longitude : null,
                'work_duration' => $workDuration,
                'status' => 'present',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Check out berhasil!',
                'data' => [
                    'check_out_time' => $attendance->check_out_time->format('H:i:s'),
                    'work_duration' => $workDuration,
                    'status' => 'present'
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
