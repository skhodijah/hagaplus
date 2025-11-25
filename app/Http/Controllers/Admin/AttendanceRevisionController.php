<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AttendanceRevision;
use App\Models\Admin\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceRevisionController extends Controller
{
    public function index()
    {
        $revisions = AttendanceRevision::with(['user', 'attendance'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);

        return view('admin.attendance.revisions.index', compact('revisions'));
    }

    public function approve(Request $request, $id)
    {
        $revision = AttendanceRevision::findOrFail($id);
        
        if ($revision->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($revision, $request) {
            // Update status revisi
            $revision->update([
                'status' => 'approved',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'review_notes' => $request->notes
            ]);

            // Update data absensi asli
            $attendance = $revision->attendance;
            $attendancePolicy = $attendance->attendancePolicy; // Menggunakan accessor yang baru dibuat

            if ($revision->revision_type === 'check_in') {
                $checkInTime = \Carbon\Carbon::parse($revision->revised_time);
                $attendance->check_in_time = $checkInTime;
                
                // Recalculate late minutes
                $lateMinutes = 0;
                if ($attendancePolicy && $attendancePolicy->start_time) {
                    $workStartTime = \Carbon\Carbon::parse($attendance->attendance_date->format('Y-m-d') . ' ' . $attendancePolicy->start_time->format('H:i:s'));
                    $lateTolerance = $attendancePolicy->late_tolerance ?? 15;
                    $graceTime = $workStartTime->copy()->addMinutes($lateTolerance);

                    if ($checkInTime->greaterThan($graceTime)) {
                        $lateMinutes = $workStartTime->diffInMinutes($checkInTime);
                    }
                }
                
                $attendance->late_minutes = $lateMinutes;
                $attendance->status = $lateMinutes > 0 ? 'late' : 'present';

                // Recalculate work duration if check out exists
                if ($attendance->check_out_time) {
                    $attendance->work_duration = $checkInTime->diffInMinutes($attendance->check_out_time);
                }

            } else {
                $checkOutTime = \Carbon\Carbon::parse($revision->revised_time);
                $attendance->check_out_time = $checkOutTime;
                
                // Recalculate work duration
                if ($attendance->check_in_time) {
                    $attendance->work_duration = $attendance->check_in_time->diffInMinutes($checkOutTime);
                }

                // Recalculate early checkout
                $earlyCheckoutMinutes = 0;
                if ($attendancePolicy && $attendancePolicy->end_time) {
                    $workEndTime = \Carbon\Carbon::parse($attendance->attendance_date->format('Y-m-d') . ' ' . $attendancePolicy->end_time->format('H:i:s'));
                    $earlyCheckoutTolerance = $attendancePolicy->early_checkout_tolerance ?? 15;
                    $graceTime = $workEndTime->copy()->subMinutes($earlyCheckoutTolerance);

                    if ($checkOutTime->lessThan($graceTime)) {
                        $earlyCheckoutMinutes = $workEndTime->diffInMinutes($checkOutTime);
                    }
                }
                $attendance->early_checkout_minutes = $earlyCheckoutMinutes;
            }
            
            $attendance->save();
        });

        return back()->with('success', 'Pengajuan edit absensi disetujui dan data absensi telah diperbarui.');
    }

    public function reject(Request $request, $id)
    {
        $revision = AttendanceRevision::findOrFail($id);

        if ($revision->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $revision->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => $request->notes
        ]);

        return back()->with('success', 'Pengajuan edit absensi ditolak.');
    }
}
