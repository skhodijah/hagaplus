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
        $instansiId = Auth::user()->instansi_id;

        $query = Leave::whereHas('user', function ($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })->with(['user', 'approver']);

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
        $employees = \App\Models\Core\User::where('role', 'employee')
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

        // Calculate days count
        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);
        $daysCount = $startDate->diffInDays($endDate) + 1;

        Leave::create([
            'user_id' => $request->user_id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'days_count' => $daysCount,
            'reason' => $request->reason,
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

        $employees = \App\Models\Core\User::where('role', 'employee')
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
        $daysCount = $startDate->diffInDays($endDate) + 1;

        $leave->update([
            'user_id' => $request->user_id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'days_count' => $daysCount,
            'reason' => $request->reason,
            'status' => $request->status ?? $leave->status,
        ]);

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
    public function approve(Leave $leave)
    {
        $instansiId = Auth::user()->instansi_id;

        // Ensure the leave belongs to the admin's instansi
        if ($leave->user && $leave->user->instansi_id !== $instansiId) {
            abort(403, 'Unauthorized action.');
        }

        $leave->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('admin.leaves.index')
            ->with('success', 'Leave request approved successfully.');
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
