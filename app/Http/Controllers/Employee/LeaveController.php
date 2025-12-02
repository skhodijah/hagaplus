<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Admin\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ApprovalService;
use Carbon\Carbon;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get all leaves for this employee
        $leaves = Leave::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate statistics
        $totalLeaves = $leaves->count();
        $approvedLeaves = $leaves->where('status', 'approved')->count();
        $pendingLeaves = $leaves->where('status', 'pending')->count();
        $rejectedLeaves = $leaves->where('status', 'rejected')->count();
        
        // Calculate leave quota
        // Default annual leave quota is 12 days per year
        $annualQuota = 12;
        
        // Calculate used leave days (only approved leaves in current year)
        $currentYear = Carbon::now()->year;
        $usedDays = Leave::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereYear('start_date', $currentYear)
            ->sum('days_count');
        
        $remainingQuota = $annualQuota - $usedDays;
        
        return view('employee.leaves.index', compact(
            'leaves',
            'totalLeaves',
            'approvedLeaves',
            'pendingLeaves',
            'rejectedLeaves',
            'annualQuota',
            'usedDays',
            'remainingQuota'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Conditional validation based on leave type
        $rules = [
            'leave_type' => 'required|in:annual,sick,maternity,emergency,other',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
        ];
        
        // Attachment is required for non-annual leave types
        if ($request->leave_type !== 'annual') {
            $rules['attachment'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:2048';
        } else {
            $rules['attachment'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048';
        }
        
        $validated = $request->validate($rules);
        
        // Calculate days count (excluding weekends and holidays)
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        
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
        
        // Handle file upload
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('leave-attachments', 'public');
        }
        
        // Create leave request
        $leave = Leave::create([
            'user_id' => Auth::id(),
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'days_count' => $daysCount,
            'reason' => $validated['reason'],
            'attachment' => $attachmentPath,
            'status' => 'pending',
        ]);

        // Create approval request using the ApprovalService
        // $approvalService = new \App\Services\ApprovalService();
        // $approvalRequest = $approvalService->createApprovalRequest($leave, 'leave', Auth::id());
        // Link the approval request to the leave
        // $leave->update(['approval_request_id' => $approvalRequest->id]);
        
        return redirect()->route('employee.leaves.index')
            ->with('success', 'Pengajuan cuti berhasil disubmit. Menunggu persetujuan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Leave $leave)
    {
        // Ensure employee can only view their own leaves
        if ($leave->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('employee.leaves.show', compact('leave'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leave $leave)
    {
        // Ensure employee can only delete their own leaves
        if ($leave->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Only allow deletion of pending leaves
        if ($leave->status !== 'pending') {
            return redirect()->route('employee.leaves.index')
                ->with('error', 'Hanya pengajuan cuti dengan status pending yang dapat dibatalkan.');
        }
        
        $leave->delete();
        
        return redirect()->route('employee.leaves.index')
            ->with('success', 'Pengajuan cuti berhasil dibatalkan.');
    }
}
