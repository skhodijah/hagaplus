<?php

namespace App\Models;

use App\Models\Core\BaseModel;
use App\Models\Core\User;
use App\Models\Admin\Employee;

class Reimbursement extends BaseModel
{
    protected $table = 'reimbursements';

    protected $fillable = [
        'user_id',
        'employee_id',
        'reference_number',
        'category',
        'description',
        'date_of_expense',
        'amount',
        'currency',
        'proof_file',
        'project_code',
        'payment_method',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        'status',
        'rejection_reason',
        'approved_amount',
        'supervisor_id',
        'manager_id',
        'finance_approver_id',
        'supervisor_approved_at',
        'manager_approved_at',
        'finance_verified_at',
        'paid_at',
    ];

    protected $casts = [
        'date_of_expense' => 'date',
        'amount' => 'decimal:2',
        'approved_amount' => 'decimal:2',
        'supervisor_approved_at' => 'datetime',
        'manager_approved_at' => 'datetime',
        'finance_verified_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function financeApprover()
    {
        return $this->belongsTo(User::class, 'finance_approver_id');
    }

    public static function generateReferenceNumber()
    {
        $prefix = 'RMB';
        $date = now()->format('Ymd');
        $last = self::whereDate('created_at', now())->count() + 1;
        return $prefix . '-' . $date . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
    }
}
