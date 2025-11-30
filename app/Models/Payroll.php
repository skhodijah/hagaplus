<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Core\User;
use App\Models\Admin\Employee;
use App\Models\Instansi;

class Payroll extends Model
{
    protected $fillable = [
        'instansi_id',
        'user_id',
        'employee_id',
        'period_year',
        'period_month',
        // Pendapatan
        'gaji_pokok',
        'tunjangan_jabatan',
        'tunjangan_makan',
        'tunjangan_transport',
        'lembur',
        'bonus',
        'reimburse',
        'thr',
        // Potongan
        'bpjs_kesehatan',
        'bpjs_tk',
        'pph21',
        'potongan_absensi',
        'kasbon',
        'potongan_lainnya',
        // Totals
        'total_pendapatan',
        'total_potongan',
        'gaji_bersih',
        // Other
        'created_date',
        'payment_date',
        'payment_status',
        'notes',
        'created_by',
        // Approval
        'approved_by',
        'approved_at',
        'approval_status',
        'rejection_reason',
        // Bank Details
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
    ];

    protected $casts = [
        'period_year' => 'integer',
        'period_month' => 'integer',
        'gaji_pokok' => 'decimal:2',
        'tunjangan_jabatan' => 'decimal:2',
        'tunjangan_makan' => 'decimal:2',
        'tunjangan_transport' => 'decimal:2',
        'lembur' => 'decimal:2',
        'bonus' => 'decimal:2',
        'reimburse' => 'decimal:2',
        'thr' => 'decimal:2',
        'bpjs_kesehatan' => 'decimal:2',
        'bpjs_tk' => 'decimal:2',
        'pph21' => 'decimal:2',
        'potongan_absensi' => 'decimal:2',
        'kasbon' => 'decimal:2',
        'potongan_lainnya' => 'decimal:2',
        'total_pendapatan' => 'decimal:2',
        'total_potongan' => 'decimal:2',
        'gaji_bersih' => 'decimal:2',
        'created_date' => 'date',
        'payment_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Calculate totals automatically
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($payroll) {
            // Calculate total pendapatan
            $payroll->total_pendapatan = 
                $payroll->gaji_pokok +
                $payroll->tunjangan_jabatan +
                $payroll->tunjangan_makan +
                $payroll->tunjangan_transport +
                $payroll->lembur +
                $payroll->bonus +
                $payroll->reimburse +
                $payroll->thr;

            // Calculate total potongan
            $payroll->total_potongan = 
                $payroll->bpjs_kesehatan +
                $payroll->bpjs_tk +
                $payroll->pph21 +
                $payroll->potongan_absensi +
                $payroll->kasbon +
                $payroll->potongan_lainnya;

            // Calculate gaji bersih (THP)
            $payroll->gaji_bersih = $payroll->total_pendapatan - $payroll->total_potongan;
        });
    }
}
