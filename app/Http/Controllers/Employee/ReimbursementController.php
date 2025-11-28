<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Reimbursement;
use App\Models\Admin\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReimbursementController extends Controller
{
    public function index()
    {
        $employee = Employee::where('user_id', Auth::id())->firstOrFail();
        $reimbursements = Reimbursement::where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('employee.reimbursements.index', compact('reimbursements'));
    }

    public function create()
    {
        $employee = Employee::with(['division', 'department', 'position', 'supervisor.user', 'manager.user'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('employee.reimbursements.create', compact('employee'));
    }

    public function store(Request $request)
    {
        $employee = Employee::where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'category' => 'required|string',
            'description' => 'required|string',
            'date_of_expense' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'proof_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'payment_method' => 'required|in:Transfer,Cash,Payroll',
        ]);

        $path = null;
        if ($request->hasFile('proof_file')) {
            $path = $request->file('proof_file')->store('reimbursements', 'public');
        }

        // Auto-fill bank details if Transfer
        $bankName = $request->bank_name;
        $bankAccount = $request->bank_account_number;
        $bankHolder = $request->bank_account_holder;

        if ($request->payment_method === 'Transfer') {
            $bankName = $employee->bank_name;
            $bankAccount = $employee->bank_account_number;
            $bankHolder = $employee->bank_account_holder;
        }

        Reimbursement::create([
            'user_id' => Auth::id(),
            'employee_id' => $employee->id,
            'reference_number' => Reimbursement::generateReferenceNumber(),
            'category' => $request->category,
            'description' => $request->description,
            'date_of_expense' => $request->date_of_expense,
            'amount' => $request->amount,
            'currency' => $request->currency ?? 'IDR',
            'proof_file' => $path,
            'project_code' => $request->project_code,
            'payment_method' => $request->payment_method,
            'bank_name' => $bankName,
            'bank_account_number' => $bankAccount,
            'bank_account_holder' => $bankHolder,
            'status' => 'pending',
            'supervisor_id' => $employee->supervisor_id, // Snapshot
            'manager_id' => $employee->manager_id, // Snapshot
        ]);

        return redirect()->route('employee.reimbursements.index')
            ->with('success', 'Reimbursement request submitted successfully.');
    }

    public function show(Reimbursement $reimbursement)
    {
        if ($reimbursement->user_id !== Auth::id()) {
            abort(403);
        }
        return view('employee.reimbursements.show', compact('reimbursement'));
    }

    public function destroy(Reimbursement $reimbursement)
    {
        if ($reimbursement->user_id !== Auth::id()) {
            abort(403);
        }

        if ($reimbursement->status !== 'pending') {
            return back()->with('error', 'Cannot delete processed reimbursement.');
        }

        if ($reimbursement->proof_file) {
            Storage::disk('public')->delete($reimbursement->proof_file);
        }

        $reimbursement->delete();

        return redirect()->route('employee.reimbursements.index')
            ->with('success', 'Reimbursement request deleted.');
    }
}
