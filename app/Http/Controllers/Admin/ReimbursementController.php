<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reimbursement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReimbursementStatusChanged;

class ReimbursementController extends Controller
{
    /**
     * Display a listing of all reimbursement requests.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        $query = Reimbursement::with(['employee.user', 'employee.department', 'supervisor.user', 'manager.user', 'financeApprover'])
            ->whereHas('employee', function($q) use ($user) {
                $q->where('instansi_id', $user->instansi_id);
            });

        // Branch filtering for non-superadmin users
        if ($user->system_role_id !== 1 && $user->employee && $user->employee->branch_id) {
            $query->whereHas('employee', function($q) use ($user) {
                $q->where('branch_id', $user->employee->branch_id);
            });
        }

        // Role-based filtering
        if ($user->system_role_id === 1) {
            // Superadmin sees everything (within their branch if restricted)
        } elseif ($user->system_role_id === 2) {
            // Admin sees everything (within their branch if restricted)
            // This includes both 'User' and 'HRD' roles
        } elseif ($employee) {
            $roleName = $employee->instansiRole->name ?? '';

            if ($roleName === 'HRD') {
                // HRD sees everything (within their branch if restricted)
            } elseif ($roleName === 'User') {
                // User (Kepala Divisi/Atasan) sees requests where they are the supervisor
                $query->where('supervisor_id', $employee->id);
            } else {
                // Regular employees (Employee role) only see their own
                $query->where('employee_id', $employee->id);
            }
        } else {
            // No employee record and not admin - shouldn't happen but safe fallback
            $query->where('id', 0);
        }

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('employee.user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $reimbursements = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.reimbursements.index', compact('reimbursements'));
    }

    /**
     * Show the details of a specific reimbursement request.
     */
    public function show(Reimbursement $reimbursement)
    {
        $reimbursement->load([
            'employee.user', 
            'employee.division', 
            'employee.department', 
            'employee.position',
            'employee.supervisor.user', 
            'employee.manager.user',
            'supervisor.user', 
            'manager.user', 
            'financeApprover'
        ]);
        
        return view('admin.reimbursements.show', compact('reimbursement'));
    }

    /**
     * Approve a reimbursement at a given level.
     * The request should include a "level" parameter: supervisor, hrd.
     */
    public function approve(Request $request, Reimbursement $reimbursement)
    {
        $level = $request->input('level');
        $user = Auth::user();

        if (!$this->canApproveAtLevel($user, $reimbursement, $level)) {
            return back()->with('error', 'You are not authorized to approve at this level.');
        }

        switch ($level) {
            case 'supervisor':
                $reimbursement->supervisor_id = $user->employee->id ?? null;
                $reimbursement->supervisor_approved_at = now();
                $reimbursement->status = 'approved_supervisor';
                break;
            case 'hrd':
                // We reuse finance_approver_id for HRD as it's the final approver
                $reimbursement->finance_approver_id = $user->id;
                $reimbursement->finance_verified_at = now();
                $reimbursement->status = 'verified_finance';
                break;
            default:
                return back()->with('error', 'Invalid approval level.');
        }

        $reimbursement->save();
        
        // Send notification
        if ($reimbursement->employee && $reimbursement->employee->user) {
            // Mail::to($reimbursement->employee->user->email)->queue(new ReimbursementStatusChanged($reimbursement));
        }

        return back()->with('success', 'Reimbursement approved at ' . $level . ' level.');
    }

    /**
     * Mark reimbursement as paid.
     */
    public function markAsPaid(Request $request, Reimbursement $reimbursement)
    {
        $user = Auth::user();
        
        // Check if user is HRD or Superadmin
        $isHRD = ($user->employee && $user->employee->instansiRole && $user->employee->instansiRole->name === 'HRD');
        $isSuperadmin = $user->system_role_id === 1;

        if (!$isHRD && !$isSuperadmin) {
            return back()->with('error', 'You are not authorized to mark this as paid.');
        }

        if ($reimbursement->status !== 'verified_finance') {
            return back()->with('error', 'Reimbursement must be approved by HRD before payment.');
        }

        // Validate payment proof for Transfer method
        if ($reimbursement->payment_method === 'Transfer') {
            $request->validate([
                'payment_proof_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);
        }

        // Handle payment proof upload
        if ($request->hasFile('payment_proof_file')) {
            $paymentProofPath = $request->file('payment_proof_file')->store('reimbursement-proofs', 'public');
            $reimbursement->payment_proof_file = $paymentProofPath;
        }

        $reimbursement->status = 'paid';
        $reimbursement->paid_at = now();
        $reimbursement->save();

        return redirect()->route('admin.reimbursements.show', $reimbursement)->with('success', 'Reimbursement marked as paid.');
    }

    /**
     * Reject a reimbursement request.
     */
    public function reject(Request $request, Reimbursement $reimbursement)
    {
        $user = Auth::user();
        $canReject = $this->canApproveAtLevel($user, $reimbursement, 'supervisor') ||
                     $this->canApproveAtLevel($user, $reimbursement, 'hrd');

        if (!$canReject) {
             return back()->with('error', 'You are not authorized to reject this request.');
        }

        $reimbursement->status = 'rejected';
        $reimbursement->rejection_reason = $request->input('reason');
        $reimbursement->save();

        // Send notification
        if ($reimbursement->employee && $reimbursement->employee->user) {
            // Mail::to($reimbursement->employee->user->email)->queue(new ReimbursementStatusChanged($reimbursement));
        }

        return back()->with('success', 'Reimbursement rejected.');
    }

    /**
     * Export reimbursements to Excel.
     */
    public function export(Request $request)
    {
        $query = Reimbursement::with(['employee.user', 'employee.department', 'employee.division'])
            ->whereHas('employee', function($q) {
                $q->where('instansi_id', Auth::user()->instansi_id);
            });

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('employee.user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $reimbursements = $query->orderBy('created_at', 'desc')->get();

        // Create CSV
        $filename = 'reimbursements_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($reimbursements) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'Reference Number',
                'Employee Name',
                'Employee ID',
                'Division',
                'Department',
                'Category',
                'Date of Expense',
                'Description',
                'Amount',
                'Currency',
                'Payment Method',
                'Bank Name',
                'Account Number',
                'Account Holder',
                'Status',
                'Submitted Date',
                'Supervisor Approved',
                'HRD Approved',
                'Paid Date',
                'Rejection Reason'
            ]);

            // Data
            foreach ($reimbursements as $reimb) {
                fputcsv($file, [
                    $reimb->reference_number,
                    $reimb->employee->user->name,
                    $reimb->employee->employee_id,
                    $reimb->employee->division->name ?? '-',
                    $reimb->employee->department->name ?? '-',
                    $reimb->category,
                    $reimb->date_of_expense->format('Y-m-d'),
                    $reimb->description,
                    $reimb->amount,
                    $reimb->currency,
                    $reimb->payment_method,
                    $reimb->bank_name ?? '-',
                    $reimb->bank_account_number ?? '-',
                    $reimb->bank_account_holder ?? '-',
                    $reimb->status,
                    $reimb->created_at->format('Y-m-d H:i:s'),
                    $reimb->supervisor_approved_at ? $reimb->supervisor_approved_at->format('Y-m-d H:i:s') : '-',
                    $reimb->finance_verified_at ? $reimb->finance_verified_at->format('Y-m-d H:i:s') : '-', // Mapped to HRD
                    $reimb->paid_at ? $reimb->paid_at->format('Y-m-d H:i:s') : '-',
                    $reimb->rejection_reason ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk approve reimbursements.
     */
    public function bulkApprove(Request $request)
    {
        $ids = $request->input('ids', []);
        $level = $request->input('level');
        $user = Auth::user();

        $reimbursements = Reimbursement::whereIn('id', $ids)
            ->whereNotIn('status', ['rejected', 'paid'])
            ->with('employee.user')
            ->get();

        $updated = 0;
        $failed = 0;

        foreach ($reimbursements as $reimbursement) {
            // Check permission for each reimbursement
            if (!$this->canApproveAtLevel($user, $reimbursement, $level)) {
                $failed++;
                continue;
            }

            switch ($level) {
                case 'supervisor':
                    if ($reimbursement->status === 'pending') {
                        $reimbursement->supervisor_id = $user->employee->id ?? null;
                        $reimbursement->supervisor_approved_at = now();
                        $reimbursement->status = 'approved_supervisor';
                        $reimbursement->save();
                        
                        // if ($reimbursement->employee && $reimbursement->employee->user) {
                        //     Mail::to($reimbursement->employee->user->email)->queue(new ReimbursementStatusChanged($reimbursement));
                        // }
                        $updated++;
                    }
                    break;
                case 'hrd':
                    // Allow approval if approved by supervisor OR if no supervisor assigned (pending)
                    if ($reimbursement->status === 'approved_supervisor' || ($reimbursement->status === 'pending' && !$reimbursement->supervisor_id)) {
                        $reimbursement->finance_approver_id = $user->id;
                        $reimbursement->finance_verified_at = now();
                        $reimbursement->status = 'verified_finance';
                        $reimbursement->save();
                        
                        // if ($reimbursement->employee && $reimbursement->employee->user) {
                        //     Mail::to($reimbursement->employee->user->email)->queue(new ReimbursementStatusChanged($reimbursement));
                        // }
                        $updated++;
                    }
                    break;
            }
        }

        $message = "{$updated} reimbursement(s) approved successfully.";
        if ($failed > 0) {
            $message .= " {$failed} request(s) were skipped due to insufficient permissions.";
        }

        return back()->with('success', $message);
    }

    /**
     * Bulk reject reimbursements.
     */
    public function bulkReject(Request $request)
    {
        $ids = $request->input('ids', []);
        $reason = $request->input('reason');
        $user = Auth::user();

        $reimbursements = Reimbursement::whereIn('id', $ids)
            ->whereNotIn('status', ['rejected', 'paid'])
            ->with('employee.user')
            ->get();

        $updated = 0;
        $failed = 0;

        foreach ($reimbursements as $reimbursement) {
            // Check if user can reject (has any approval right)
            $canReject = $this->canApproveAtLevel($user, $reimbursement, 'supervisor') ||
                         $this->canApproveAtLevel($user, $reimbursement, 'hrd');

            if (!$canReject) {
                $failed++;
                continue;
            }

            $reimbursement->status = 'rejected';
            $reimbursement->rejection_reason = $reason;
            $reimbursement->save();

            // if ($reimbursement->employee && $reimbursement->employee->user) {
            //     Mail::to($reimbursement->employee->user->email)->queue(new ReimbursementStatusChanged($reimbursement));
            // }
            $updated++;
        }

        $message = "{$updated} reimbursement(s) rejected successfully.";
        if ($failed > 0) {
            $message .= " {$failed} request(s) were skipped due to insufficient permissions.";
        }

        return back()->with('success', $message);
    }

    /**
     * Check if user is authorized to approve at a specific level for a specific reimbursement.
     */
    private function canApproveAtLevel($user, $reimbursement, $level)
    {
        // Superadmin override (system_role_id = 1)
        if ($user->system_role_id === 1) {
            return true;
        }

        // Ensure user has employee record
        if (!$user->employee) {
            return false;
        }

        $roleName = $user->employee->instansiRole->name ?? '';

        switch ($level) {
            case 'supervisor':
                // Check if user is the assigned supervisor for this reimbursement
                // OR if user has 'User' role (Kepala Divisi/Atasan)
                $isSupervisor = $reimbursement->supervisor_id === $user->employee->id;
                $isUserRole = $roleName === 'User';
                
                return $isSupervisor || $isUserRole;
            
            case 'hrd':
                // Only HRD role can approve at HRD level
                return $roleName === 'HRD';
            
            default:
                return false;
        }
    }
}
