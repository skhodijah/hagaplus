<?php

namespace App\Models\Admin;

use App\Models\Core\BaseModel;

class Payroll extends BaseModel
{
    protected $fillable = [
        'employee_id',
        'period_start',
        'period_end',
        'total_hours',
        'overtime_hours',
        'regular_pay',
        'overtime_pay',
        'total_pay',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'total_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'regular_pay' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'total_pay' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
