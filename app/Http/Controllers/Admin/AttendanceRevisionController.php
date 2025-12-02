<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AttendanceRevision;
use App\Models\Admin\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceRevisionController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;
        $instansiId = $user->instansi_id;

        $query = AttendanceRevision::with(['user.employee.department', 'user.employee.division', 'user.employee.position', 'attendance'])
            ->whereHas('user', function ($q) use ($instansiId) {
                $q->where('instansi_id', $instansiId);
            });

        // Branch filtering
        if ($user->system_role_id !== 1 && $user->employee && $user->employee->branch_id) {
            $query->whereHas('user.employee', function($q) use ($user) {
                $q->where('branch_id', $user->employee->branch_id);
            });
        }

        // Role-based filtering
        if ($user->system_role_id === 1 || $user->system_role_id === 2) {
            // Admin/Superadmin sees all
        } elseif ($employee) {
            $roleName = $employee->instansiRole->name ?? '';
            if ($roleName === 'HRD') {
                // HRD sees all
            } elseif ($roleName === 'User') {
                // Supervisor sees their subordinates
                $query->whereHas('user.employee', function($q) use ($employee) {
                    $q->where('supervisor_id', $employee->id);
                });
            } else {
                // Employee sees their own
                $query->where('user_id', $user->id);
            }
        } else {
            $query->where('user_id', $user->id);
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $revisions = $query->latest()->paginate(10);

        return view('admin.attendance.revisions.index', compact('revisions'));
    }

    public function approve(Request $request, $id)
    {
        $revision = AttendanceRevision::findOrFail($id);
        $level = $request->input('level');
        $user = auth()->user();

        DB::transaction(function () use ($revision, $request, $level, $user) {
            if ($level === 'supervisor') {
                $revision->update([
                    'status' => 'approved_supervisor',
                    'supervisor_id' => $user->employee->id ?? null,
                    'supervisor_approved_at' => now(),
                    'supervisor_note' => $request->notes
                ]);
            } elseif ($level === 'hrd') {
                $revision->update([
                    'status' => 'approved',
                    'hrd_id' => $user->id,
                    'hrd_approved_at' => now(),
                    'hrd_note' => $request->notes,
                    'reviewed_by' => $user->id,
                    'reviewed_at' => now(),
                ]);

                // Apply changes to attendance
                $this->applyRevisionToAttendance($revision);
            }
        });

        return back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    protected function applyRevisionToAttendance($revision)
    {
        $attendance = $revision->attendance;
        $attendancePolicy = $attendance->attendancePolicy;

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
    }

    public function reject(Request $request, $id)
    {
        $revision = AttendanceRevision::findOrFail($id);

        $revision->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => $request->notes
        ]);

        return back()->with('success', 'Pengajuan edit absensi ditolak.');
    }
}
