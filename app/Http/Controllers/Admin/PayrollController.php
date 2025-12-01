<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Models\Core\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;

        $query = Payroll::whereHas('user', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })->with(['user', 'approver.employee.position']);

        // Filter by year
        if ($request->filled('year')) {
            $query->where('period_year', $request->year);
        }

        // Filter by month
        if ($request->filled('month')) {
            $query->where('period_month', $request->month);
        }

        // Filter by payment status
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        // Filter by approval status
        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        $payrolls = $query->orderBy('period_year', 'desc')
            ->orderBy('period_month', 'desc')
            ->paginate(15);

        return view('admin.payroll.index', compact('payrolls'));
    }

    public function export(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;

        $query = Payroll::whereHas('user', function($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })->with(['user.employee.division', 'user.employee.position', 'approver']);

        // Apply same filters as index
        if ($request->filled('year')) {
            $query->where('period_year', $request->year);
        }
        if ($request->filled('month')) {
            $query->where('period_month', $request->month);
        }
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }
        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        $export = new \App\Exports\PayrollExport($query);
        return $export->download();
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020',
        ]);

        $user = User::with('employee')->findOrFail($request->user_id);
        $employee = $user->employee;

        if (!$employee) {
            return response()->json(['error' => 'Employee data not found'], 404);
        }

        // Basic Salary & Allowances from Employee record
        $data = [
            'gaji_pokok' => $employee->salary ?? 0,
            'tunjangan_jabatan' => $employee->tunjangan_jabatan ?? 0,
            'tunjangan_makan' => $employee->tunjangan_makan ?? 0,
            'tunjangan_transport' => $employee->tunjangan_transport ?? 0,
            'tunjangan_hadir' => $employee->tunjangan_hadir ?? 0,
            'bpjs_kesehatan' => 0,
            'bpjs_tk' => 0,
            'potongan_absensi' => 0,
            'lembur' => 0,
        ];

        // Calculate Attendance based deductions/additions
        $startOfMonth = \Carbon\Carbon::createFromDate($request->year, $request->month, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $attendances = \App\Models\Admin\Attendance::where('user_id', $user->id)
            ->whereBetween('attendance_date', [$startOfMonth, $endOfMonth])
            ->get();

        // 1. Potongan Absensi
        // Count absent days and late minutes
        $absentCount = $attendances->where('status', 'absent')->count();
        $lateMinutes = $attendances->sum('late_minutes');
        
        // Deduction rules (Placeholder values - ideally should be in settings)
        $deductionPerAbsent = 100000; // Example: 100k per day
        $deductionPerLateMinute = 1000; // Example: 1k per minute

        $data['potongan_absensi'] = ($absentCount * $deductionPerAbsent) + ($lateMinutes * $deductionPerLateMinute);

        // 2. Lembur (Overtime)
        // Assuming overtime_duration is in minutes
        $overtimeMinutes = $attendances->sum('overtime_duration');
        $overtimeRatePerHour = 20000; // Example: 20k per hour
        $data['lembur'] = ($overtimeMinutes / 60) * $overtimeRatePerHour;

        // 3. BPJS Calculations (Standard rates)
        // BPJS Kesehatan: 1% paid by employee
        $data['bpjs_kesehatan'] = $data['gaji_pokok'] * 0.01;
        
        // BPJS Ketenagakerjaan: JHT 2% + JP 1% = 3% paid by employee
        $data['bpjs_tk'] = $data['gaji_pokok'] * 0.03;

        // 4. PPh21 (Simplified placeholder)
        // This requires complex calculation (PTKP, progressive rates). 
        // For now set to 0 or simple 5% of taxable income if > PTKP.
        $data['pph21'] = 0; 

        // Add details for frontend display
        $data['details'] = [
            'absent_days' => $absentCount,
            'late_minutes' => $lateMinutes,
            'overtime_minutes' => $overtimeMinutes,
            'deduction_per_absent' => $deductionPerAbsent,
            'deduction_per_late_minute' => $deductionPerLateMinute,
            'overtime_rate_per_hour' => $overtimeRatePerHour,
        ];

        // Add bank details
        $data['bank_name'] = $employee->bank_name;
        $data['bank_account_number'] = $employee->bank_account_number;
        $data['bank_account_holder'] = $employee->bank_account_holder;

        // Get reimbursements for this period with payment_method = 'Payroll' and status = 'paid'
        $reimbursements = \App\Models\Reimbursement::where('user_id', $user->id)
            ->where('payment_method', 'Payroll')
            ->where('status', 'paid')
            ->whereYear('paid_at', $request->year)
            ->whereMonth('paid_at', $request->month)
            ->get();

        $totalReimbursement = $reimbursements->sum('amount');
        $data['reimburse'] = $totalReimbursement;
        
        // Add reimbursement details
        $data['reimbursement_details'] = $reimbursements->map(function($reimb) {
            return [
                'id' => $reimb->id,
                'reference_number' => $reimb->reference_number,
                'category' => $reimb->category,
                'description' => $reimb->description,
                'date_of_expense' => $reimb->date_of_expense->format('d M Y'),
                'amount' => $reimb->amount,
                'paid_at' => $reimb->paid_at->format('d M Y'),
            ];
        });

        return response()->json($data);
    }

    public function create()
    {
        $employees = User::whereHas('systemRole', function($q) {
                            $q->where('slug', 'employee');
                        })
                        ->where('instansi_id', Auth::user()->instansi_id)
                        ->get();
        return view('admin.payroll.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;

        $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($instansiId) {
                    $user = User::find($value);
                    if (!$user || $user->instansi_id !== $instansiId) {
                        $fail('The selected employee does not belong to your institution.');
                    }
                },
            ],
            'period_year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'period_month' => 'required|integer|min:1|max:12',
            // Pendapatan
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan_jabatan' => 'nullable|numeric|min:0',
            'tunjangan_makan' => 'nullable|numeric|min:0',
            'tunjangan_transport' => 'nullable|numeric|min:0',
            'lembur' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'reimburse' => 'nullable|numeric|min:0',
            'thr' => 'nullable|numeric|min:0',
            // Potongan
            'bpjs_kesehatan' => 'nullable|numeric|min:0',
            'bpjs_tk' => 'nullable|numeric|min:0',
            'pph21' => 'nullable|numeric|min:0',
            'potongan_absensi' => 'nullable|numeric|min:0',
            'kasbon' => 'nullable|numeric|min:0',
            'potongan_lainnya' => 'nullable|numeric|min:0',
            // Other
            'notes' => 'nullable|string|max:1000',
            // payment_date and payment_status tidak perlu validasi karena auto-set
        ]);

        $user = User::with('employee')->findOrFail($request->user_id);
        $employee = $user->employee;

        Payroll::create([
            'instansi_id' => $instansiId,
            'user_id' => $request->user_id,
            'period_year' => $request->period_year,
            'period_month' => $request->period_month,
            // Pendapatan
            'gaji_pokok' => $request->gaji_pokok ?? 0,
            'tunjangan_jabatan' => $request->tunjangan_jabatan ?? 0,
            'tunjangan_makan' => $request->tunjangan_makan ?? 0,
            'tunjangan_transport' => $request->tunjangan_transport ?? 0,
            'lembur' => $request->lembur ?? 0,
            'bonus' => $request->bonus ?? 0,
            'reimburse' => $request->reimburse ?? 0,
            'thr' => $request->thr ?? 0,
            // Potongan
            'bpjs_kesehatan' => $request->bpjs_kesehatan ?? 0,
            'bpjs_tk' => $request->bpjs_tk ?? 0,
            'pph21' => $request->pph21 ?? 0,
            'potongan_absensi' => $request->potongan_absensi ?? 0,
            'kasbon' => $request->kasbon ?? 0,
            'potongan_lainnya' => $request->potongan_lainnya ?? 0,
            // Bank Details from Employee
            'bank_name' => $employee->bank_name ?? null,
            'bank_account_number' => $employee->bank_account_number ?? null,
            'bank_account_holder' => $employee->bank_account_holder ?? null,
            // Other
            'created_date' => now()->toDateString(), // Tanggal draft dibuat
            'payment_date' => null, // Akan diisi saat status diubah ke 'paid'
            'payment_status' => 'draft',
            'approval_status' => 'pending',
            'notes' => $request->notes,
            'created_by' => Auth::id(),
            // totals will be auto-calculated by model boot method
        ]);

        return redirect()->route('admin.payroll.index')->with('success', 'Slip gaji berhasil dibuat.');
    }

    public function show($id)
    {
        $payroll = Payroll::whereHas('user', function($q) {
            $q->where('instansi_id', Auth::user()->instansi_id);
        })->with(['user.instansi', 'user.employee.division', 'user.employee.position', 'approver.employee.position', 'creator'])->findOrFail($id);

        return view('admin.payroll.show', compact('payroll'));
    }

    public function edit($id)
    {
        $payroll = Payroll::whereHas('user', function($q) {
            $q->where('instansi_id', Auth::user()->instansi_id);
        })->findOrFail($id);

        $employees = User::whereHas('systemRole', function($q) {
                            $q->where('slug', 'employee');
                        })
                        ->where('instansi_id', Auth::user()->instansi_id)
                        ->get();

        return view('admin.payroll.edit', compact('payroll', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $payroll = Payroll::whereHas('user', function($q) {
            $q->where('instansi_id', Auth::user()->instansi_id);
        })->findOrFail($id);

        $instansiId = Auth::user()->instansi_id;

        $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($instansiId) {
                    $user = User::find($value);
                    if (!$user || $user->instansi_id !== $instansiId) {
                        $fail('The selected employee does not belong to your institution.');
                    }
                },
            ],
            'period_year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'period_month' => 'required|integer|min:1|max:12',
            // Pendapatan
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan_jabatan' => 'nullable|numeric|min:0',
            'tunjangan_makan' => 'nullable|numeric|min:0',
            'tunjangan_transport' => 'nullable|numeric|min:0',
            'lembur' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'reimburse' => 'nullable|numeric|min:0',
            'thr' => 'nullable|numeric|min:0',
            // Potongan
            'bpjs_kesehatan' => 'nullable|numeric|min:0',
            'bpjs_tk' => 'nullable|numeric|min:0',
            'pph21' => 'nullable|numeric|min:0',
            'potongan_absensi' => 'nullable|numeric|min:0',
            'kasbon' => 'nullable|numeric|min:0',
            'potongan_lainnya' => 'nullable|numeric|min:0',
            // Other
            'notes' => 'nullable|string|max:1000',
            // payment_date and payment_status diubah via bulk action, tidak di edit form
        ]);

        $payroll->update([
            'user_id' => $request->user_id,
            'period_year' => $request->period_year,
            'period_month' => $request->period_month,
            // Pendapatan
            'gaji_pokok' => $request->gaji_pokok ?? 0,
            'tunjangan_jabatan' => $request->tunjangan_jabatan ?? 0,
            'tunjangan_makan' => $request->tunjangan_makan ?? 0,
            'tunjangan_transport' => $request->tunjangan_transport ?? 0,
            'lembur' => $request->lembur ?? 0,
            'bonus' => $request->bonus ?? 0,
            'reimburse' => $request->reimburse ?? 0,
            'thr' => $request->thr ?? 0,
            // Potongan
            'bpjs_kesehatan' => $request->bpjs_kesehatan ?? 0,
            'bpjs_tk' => $request->bpjs_tk ?? 0,
            'pph21' => $request->pph21 ?? 0,
            'potongan_absensi' => $request->potongan_absensi ?? 0,
            'kasbon' => $request->kasbon ?? 0,
            'potongan_lainnya' => $request->potongan_lainnya ?? 0,
            // Other
            'notes' => $request->notes,
            // payment_date and payment_status diubah via bulk action di index
            // totals will be auto-calculated by model boot method
        ]);

        return redirect()->route('admin.payroll.show', $payroll)->with('success', 'Slip gaji berhasil diupdate.');
    }

    public function destroy($id)
    {
        $payroll = Payroll::whereHas('user', function($q) {
            $q->where('instansi_id', Auth::user()->instansi_id);
        })->findOrFail($id);

        $payroll->delete();

        return redirect()->route('admin.payroll.index')->with('success', 'Payroll record deleted successfully.');
    }

    public function print($id)
    {
        $payroll = Payroll::whereHas('user', function($q) {
            $q->where('instansi_id', Auth::user()->instansi_id);
        })->with([
            'user' => function($query) {
                $query->with(['instansi', 'employee.division', 'employee.position']);
            },
            'approver.employee.position'
        ])->findOrFail($id);

        // Debug: Uncomment to check loaded data
        // dd($payroll->user->instansi);

        return view('admin.payroll.print', compact('payroll'));
    }

    public function bulkApprove(Request $request)
    {
        // Only Manager and Admin Instansi (not employee) can approve
        $user = Auth::user();
        $canApprove = $user->hasRole('manager') || ($user->hasRole('admin') && !$user->employee);
        
        if (!$canApprove) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk approve payroll.');
        }

        $request->validate([
            'payroll_ids' => 'required|array',
            'payroll_ids.*' => 'exists:payrolls,id'
        ]);

        $updated = Payroll::whereIn('id', $request->payroll_ids)
            ->whereHas('user', function($q) {
                $q->where('instansi_id', Auth::user()->instansi_id);
            })
            ->where('approval_status', 'pending')
            ->update([
                'approval_status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'payment_status' => 'processed'
            ]);

        return redirect()->back()->with('success', "$updated slip gaji berhasil disetujui.");
    }

    public function bulkReject(Request $request)
    {
        // Only Manager and Admin Instansi (not employee) can reject
        $user = Auth::user();
        $canApprove = $user->hasRole('manager') || ($user->hasRole('admin') && !$user->employee);
        
        if (!$canApprove) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk reject payroll.');
        }

        $request->validate([
            'payroll_ids' => 'required|array',
            'payroll_ids.*' => 'exists:payrolls,id',
            'rejection_reason' => 'required|string|max:500'
        ]);

        $updated = Payroll::whereIn('id', $request->payroll_ids)
            ->whereHas('user', function($q) {
                $q->where('instansi_id', Auth::user()->instansi_id);
            })
            ->where('approval_status', 'pending')
            ->update([
                'approval_status' => 'rejected',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'rejection_reason' => $request->rejection_reason
            ]);

        return redirect()->back()->with('success', "$updated slip gaji berhasil ditolak.");
    }

    public function bulkMarkAsPaid(Request $request)
    {
        $request->validate([
            'payroll_ids' => 'required|array',
            'payroll_ids.*' => 'exists:payrolls,id'
        ]);

        $updated = Payroll::whereIn('id', $request->payroll_ids)
            ->whereHas('user', function($q) {
                $q->where('instansi_id', Auth::user()->instansi_id);
            })
            ->where('approval_status', 'approved') // Hanya yang sudah approved
            ->where('payment_status', '!=', 'paid') // Belum paid
            ->update([
                'payment_status' => 'paid',
                'payment_date' => now()->toDateString() // Set tanggal pembayaran
            ]);

        return redirect()->back()->with('success', "$updated slip gaji berhasil ditandai sebagai dibayar.");
    }
}

