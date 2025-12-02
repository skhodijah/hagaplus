<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Leave;
use App\Http\Requests\StoreLeaveRequest;
use App\Http\Requests\UpdateLeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        $instansiId = $user->instansi_id;

        $query = Leave::with(['user.employee.department', 'user.employee.division', 'user.employee.position'])
            ->whereHas('user', function ($q) use ($instansiId) {
                $q->where('instansi_id', $instansiId);
            });

        // Branch filtering for non-superadmin users
        if ($user->system_role_id !== 1 && $user->employee && $user->employee->branch_id) {
            $query->whereHas('user.employee', function($q) use ($user) {
                $q->where('branch_id', $user->employee->branch_id);
            });
        }

        // Role-based filtering
        if ($user->system_role_id === 1) {
            // Superadmin sees everything
        } elseif ($user->system_role_id === 2) {
            // Admin sees everything
        } elseif ($employee) {
            $roleName = $employee->instansiRole->name ?? '';

            if ($roleName === 'HRD') {
                // HRD sees everything
            } elseif ($roleName === 'User') {
                // User (Kepala Divisi/Atasan) sees requests where they are the supervisor
                // We need to check if the current user is the supervisor of the leave requester
                $query->whereHas('user.employee', function($q) use ($employee) {
                    $q->where('supervisor_id', $employee->id);
                });
            } else {
                // Regular employees only see their own
                $query->where('user_id', $user->id);
            }
        } else {
             // Fallback
             $query->where('user_id', $user->id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by leave type
        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        // Search by employee name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $leaves = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.leaves.index', compact('leaves'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $instansiId = Auth::user()->instansi_id;
        $employees = \App\Models\Core\User::whereHas('systemRole', function($q) {
                $q->where('slug', 'employee');
            })
            ->where('instansi_id', $instansiId)
            ->orderBy('name')
            ->get();

        return view('admin.leaves.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeaveRequest $request)
    {
        $instansiId = Auth::user()->instansi_id;

        // Validate that user belongs to admin's instansi
        $user = \App\Models\Core\User::findOrFail($request->user_id);
        if ($user->instansi_id !== $instansiId) {
            return back()->withErrors(['user_id' => 'The selected employee does not belong to your institution.'])->withInput();
        }

        // Calculate days count (excluding weekends and holidays)
        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);
        
        // Fetch holidays that overlap with the requested range
        $holidayRanges = \App\Models\Admin\Holiday::where(function($query) use ($startDate, $endDate) {
            $query->whereBetween('start_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                  ->orWhereBetween('end_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                  ->orWhere(function($q) use ($startDate, $endDate) {
                      $q->where('start_date', '<=', $startDate->format('Y-m-d'))
                        ->where('end_date', '>=', $endDate->format('Y-m-d'));
                  });
        })->get();

        // Expand holiday ranges into a set of blocked dates
        $blockedDates = [];
        foreach ($holidayRanges as $holiday) {
            $period = \Carbon\CarbonPeriod::create($holiday->start_date, $holiday->end_date);
            foreach ($period as $date) {
                $blockedDates[] = $date->format('Y-m-d');
            }
        }
        $blockedDates = array_unique($blockedDates);

        $daysCount = 0;
        
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            // Only count weekdays (Monday to Friday) AND non-holidays
            if ($date->isWeekday() && !in_array($date->format('Y-m-d'), $blockedDates)) {
                $daysCount++;
            }
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('leave-attachments', 'public');
        }

        Leave::create([
            'user_id' => $request->user_id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'days_count' => $daysCount,
            'reason' => $request->reason,
            'attachment' => $attachmentPath,
            'status' => $request->status ?? 'pending',
        ]);

        return redirect()->route('admin.leaves.index')
            ->with('success', 'Leave request created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Leave $leave)
    {
        $instansiId = Auth::user()->instansi_id;

        // Ensure the leave belongs to the admin's instansi
        if ($leave->user && $leave->user->instansi_id !== $instansiId) {
            abort(403, 'Unauthorized action.');
        }

        $leave->load(['user', 'approver']);

        return view('admin.leaves.show', compact('leave'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Leave $leave)
    {
        $instansiId = Auth::user()->instansi_id;

        // Ensure the leave belongs to the admin's instansi
        if ($leave->user && $leave->user->instansi_id !== $instansiId) {
            abort(403, 'Unauthorized action.');
        }

        $employees = \App\Models\Core\User::whereHas('systemRole', function($q) {
                $q->where('slug', 'employee');
            })
            ->where('instansi_id', $instansiId)
            ->orderBy('name')
            ->get();

        $leave->load(['user']);

        return view('admin.leaves.edit', compact('leave', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeaveRequest $request, Leave $leave)
    {
        $instansiId = Auth::user()->instansi_id;

        // Ensure the leave belongs to the admin's instansi
        if ($leave->user && $leave->user->instansi_id !== $instansiId) {
            abort(403, 'Unauthorized action.');
        }

        // Calculate days count if dates changed
        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);
        
        // Fetch holidays that overlap with the requested range
        $holidayRanges = \App\Models\Admin\Holiday::where(function($query) use ($startDate, $endDate) {
            $query->whereBetween('start_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                  ->orWhereBetween('end_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                  ->orWhere(function($q) use ($startDate, $endDate) {
                      $q->where('start_date', '<=', $startDate->format('Y-m-d'))
                        ->where('end_date', '>=', $endDate->format('Y-m-d'));
                  });
        })->get();

        // Expand holiday ranges into a set of blocked dates
        $blockedDates = [];
        foreach ($holidayRanges as $holiday) {
            $period = \Carbon\CarbonPeriod::create($holiday->start_date, $holiday->end_date);
            foreach ($period as $date) {
                $blockedDates[] = $date->format('Y-m-d');
            }
        }
        $blockedDates = array_unique($blockedDates);

        $daysCount = 0;
        
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            // Only count weekdays (Monday to Friday) AND non-holidays
            if ($date->isWeekday() && !in_array($date->format('Y-m-d'), $blockedDates)) {
                $daysCount++;
            }
        }

        $data = [
            'user_id' => $request->user_id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'days_count' => $daysCount,
            'reason' => $request->reason,
            'status' => $request->status ?? $leave->status,
        ];

        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($leave->attachment && \Illuminate\Support\Facades\Storage::disk('public')->exists($leave->attachment)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($leave->attachment);
            }
            $data['attachment'] = $request->file('attachment')->store('leave-attachments', 'public');
        }

        $leave->update($data);

        return redirect()->route('admin.leaves.index')
            ->with('success', 'Leave request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leave $leave)
    {
        $instansiId = Auth::user()->instansi_id;

        // Ensure the leave belongs to the admin's instansi
        if ($leave->user && $leave->user->instansi_id !== $instansiId) {
            abort(403, 'Unauthorized action.');
        }

        $leave->delete();

        return redirect()->route('admin.leaves.index')
            ->with('success', 'Leave request deleted successfully.');
    }

    /**
     * Approve a leave request.
     */
    public function approve(Request $request, Leave $leave)
    {
        $level = $request->input('level');
        $user = Auth::user();

        if (!$this->canApproveAtLevel($user, $leave, $level)) {
            return back()->with('error', 'You are not authorized to approve at this level.');
        }

        $note = $request->input('note');

        switch ($level) {
            case 'supervisor':
                $leave->supervisor_id = $user->employee->id ?? null;
                $leave->supervisor_approved_at = now();
                $leave->supervisor_note = $note;
                $leave->status = 'approved_supervisor';
                break;
            case 'hrd':
                $leave->hrd_id = $user->id;
                $leave->hrd_approved_at = now();
                $leave->hrd_note = $note;
                $leave->approved_by = $user->id; // Legacy column
                $leave->approved_at = now(); // Legacy column
                $leave->status = 'approved'; // Final status
                break;
            default:
                return back()->with('error', 'Invalid approval level.');
        }

        $leave->save();

        return back()->with('success', 'Leave request approved at ' . $level . ' level.');
    }

    /**
     * Check if user is authorized to approve at a specific level for a specific leave.
     */
    private function canApproveAtLevel($user, $leave, $level)
    {
        // Superadmin override (system_role_id = 1)
        if ($user->system_role_id === 1) {
            return true;
        }

        // Admin override (system_role_id = 2) - Acts as HRD/Boss
        if ($user->system_role_id === 2) {
             // Can always approve as HRD
             if ($level === 'hrd') {
                 return true;
             }
             // Can approve as supervisor if no supervisor assigned or as override
             if ($level === 'supervisor') {
                 return true;
             }
        }

        // Ensure user has employee record for other roles
        if (!$user->employee) {
            return false;
        }

        $roleName = $user->employee->instansiRole->name ?? '';

        switch ($level) {
            case 'supervisor':
                // Check if user is the assigned supervisor for this leave requester
                // OR if user has 'User' role (Kepala Divisi/Atasan)
                $requesterSupervisorId = $leave->user->employee->supervisor_id ?? null;
                $isSupervisor = $requesterSupervisorId === $user->employee->id;
                $isUserRole = $roleName === 'User';
                
                return $isSupervisor || $isUserRole;
            
            case 'hrd':
                // Only HRD role can approve at HRD level
                return $roleName === 'HRD';
            
            default:
                return false;
        }
    }

    /**
     * Reject a leave request.
     */
    public function reject(Request $request, Leave $leave)
    {
        $instansiId = Auth::user()->instansi_id;

        // Ensure the leave belongs to the admin's instansi
        if ($leave->user && $leave->user->instansi_id !== $instansiId) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'rejected_reason' => 'required|string|max:500',
        ]);

        $leave->update([
            'status' => 'rejected',
            'rejected_reason' => $request->rejected_reason,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('admin.leaves.index')
            ->with('success', 'Leave request rejected successfully.');
    }
}
