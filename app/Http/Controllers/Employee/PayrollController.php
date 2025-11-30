<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index()
    {
        $payrolls = \App\Models\Payroll::where('user_id', auth()->id())
            ->where('payment_status', 'paid') // Only show paid/finalized payrolls
            ->where('approval_status', 'approved')
            ->orderBy('period_year', 'desc')
            ->orderBy('period_month', 'desc')
            ->paginate(10);

        return view('employee.payroll.index', compact('payrolls'));
    }

    public function show($id)
    {
        $payroll = \App\Models\Payroll::where('user_id', auth()->id())
            ->where('payment_status', 'paid')
            ->where('approval_status', 'approved')
            ->with(['user.instansi', 'user.employee.division', 'user.employee.position', 'approver.employee.position'])
            ->findOrFail($id);

        return view('employee.payroll.show', compact('payroll'));
    }

    public function print($id)
    {
        $payroll = \App\Models\Payroll::where('user_id', auth()->id())
            ->where('payment_status', 'paid')
            ->where('approval_status', 'approved')
            ->with(['user.instansi', 'user.employee.division', 'user.employee.position', 'approver.employee.position'])
            ->findOrFail($id);

        return view('admin.payroll.print', compact('payroll'));
    }
}
