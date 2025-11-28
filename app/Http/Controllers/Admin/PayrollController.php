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
        $query = Payroll::with('user')
                       ->whereHas('user', function($q) {
                           $q->where('instansi_id', Auth::user()->instansi_id);
                       });

        // Filter by period if provided
        if ($request->filled('year')) {
            $query->where('period_year', $request->year);
        }
        if ($request->filled('month')) {
            $query->where('period_month', $request->month);
        }

        // Filter by payment status
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        $payrolls = $query->orderBy('period_year', 'desc')
                          ->orderBy('period_month', 'desc')
                          ->paginate(15);

        return view('admin.payroll.index', compact('payrolls'));
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
            'payment_date' => 'nullable|date',
            'payment_status' => 'required|in:draft,processed,paid',
            'notes' => 'nullable|string|max:1000',
        ]);

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
            // Other
            'payment_date' => $request->payment_date,
            'payment_status' => $request->payment_status,
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
        })->with('user', 'creator')->findOrFail($id);

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
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|array',
            'deductions' => 'nullable|array',
            'overtime_amount' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'payment_status' => 'required|in:draft,processed,paid',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Calculate totals
        $allowances = $request->allowances ?? [];
        $deductions = $request->deductions ?? [];

        $totalAllowances = array_sum($allowances);
        $totalDeductions = array_sum($deductions);

        $totalGross = $request->basic_salary + $totalAllowances + ($request->overtime_amount ?? 0);
        $netSalary = $totalGross - $totalDeductions;

        $payroll->update([
            'user_id' => $request->user_id,
            'period_year' => $request->period_year,
            'period_month' => $request->period_month,
            'basic_salary' => $request->basic_salary,
            'allowances' => $allowances,
            'deductions' => $deductions,
            'overtime_amount' => $request->overtime_amount ?? 0,
            'total_gross' => $totalGross,
            'total_deductions' => $totalDeductions,
            'net_salary' => $netSalary,
            'payment_date' => $request->payment_date,
            'payment_status' => $request->payment_status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.payroll.index')->with('success', 'Payroll record updated successfully.');
    }

    public function destroy($id)
    {
        $payroll = Payroll::whereHas('user', function($q) {
            $q->where('instansi_id', Auth::user()->instansi_id);
        })->findOrFail($id);

        $payroll->delete();

        return redirect()->route('admin.payroll.index')->with('success', 'Payroll record deleted successfully.');
    }
}
