<?php

namespace App\Models\Admin;

use App\Models\Core\BaseModel;

class Payroll extends BaseModel
{
    protected $table = 'payrolls';

    protected $fillable = [
        'user_id',
        'period_year',
        'period_month',
        'basic_salary',
        'allowances',
        'deductions',
        'overtime_amount',
        'total_gross',
        'total_deductions',
        'net_salary',
        'payment_date',
        'payment_status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'period_year' => 'integer',
        'period_month' => 'integer',
        'basic_salary' => 'decimal:2',
        'allowances' => 'json',
        'deductions' => 'json',
        'overtime_amount' => 'decimal:2',
        'total_gross' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\Core\User::class);
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\Core\User::class, 'created_by');
    }
}
