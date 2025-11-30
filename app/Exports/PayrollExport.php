<?php

namespace App\Exports;

use App\Models\Payroll;
use Illuminate\Support\Facades\Auth;

class PayrollExport
{
    protected $query;

    public function __construct($query = null)
    {
        $this->query = $query;
    }

    public function download()
    {
        $payrolls = $this->query ?? Payroll::whereHas('user', function($q) {
            $q->where('instansi_id', Auth::user()->instansi_id);
        })->with(['user.employee.division', 'user.employee.position', 'approver'])->get();

        $filename = 'payroll_' . date('Y-m-d_His') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // UTF-8 BOM for Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Header
        fputcsv($output, [
            'Employee ID',
            'Employee Name',
            'Email',
            'Division',
            'Position',
            'Period',
            'Bank Name',
            'Account Number',
            'Account Holder',
            'Gaji Pokok',
            'Tunjangan Jabatan',
            'Tunjangan Makan',
            'Tunjangan Transport',
            'Lembur',
            'Bonus',
            'Reimburse',
            'THR',
            'Total Pendapatan',
            'BPJS Kesehatan',
            'BPJS TK',
            'PPh21',
            'Potongan Absensi',
            'Kasbon',
            'Potongan Lainnya',
            'Total Potongan',
            'Gaji Bersih',
            'Payment Status',
            'Approval Status',
            'Approved By',
            'Created Date',
            'Approved At',
            'Payment Date',
        ]);
        
        // Data
        foreach ($payrolls as $payroll) {
            fputcsv($output, [
                $payroll->user->employee->employee_id ?? '-',
                $payroll->user->name,
                $payroll->user->email,
                $payroll->user->employee->division->name ?? '-',
                $payroll->user->employee->position->name ?? '-',
                date('F Y', mktime(0, 0, 0, $payroll->period_month, 1, $payroll->period_year)),
                $payroll->bank_name ?? '-',
                $payroll->bank_account_number ?? '-',
                $payroll->bank_account_holder ?? '-',
                $payroll->gaji_pokok,
                $payroll->tunjangan_jabatan,
                $payroll->tunjangan_makan,
                $payroll->tunjangan_transport,
                $payroll->lembur,
                $payroll->bonus,
                $payroll->reimburse,
                $payroll->thr,
                $payroll->total_pendapatan,
                $payroll->bpjs_kesehatan,
                $payroll->bpjs_tk,
                $payroll->pph21,
                $payroll->potongan_absensi,
                $payroll->kasbon,
                $payroll->potongan_lainnya,
                $payroll->total_potongan,
                $payroll->gaji_bersih,
                ucfirst($payroll->payment_status),
                ucfirst($payroll->approval_status),
                $payroll->approver->name ?? '-',
                $payroll->created_date ? $payroll->created_date->format('Y-m-d') : '-',
                $payroll->approved_at ? $payroll->approved_at->format('Y-m-d H:i') : '-',
                $payroll->payment_date ? $payroll->payment_date->format('Y-m-d') : '-',
            ]);
        }
        
        fclose($output);
        exit;
    }
}
