<?php

namespace App\Models\Admin;

use App\Models\Core\BaseModel;

class Attendance extends BaseModel
{
    protected $table = 'attendances';

    protected $fillable = [
        'user_id',
        'branch_id',
        'attendance_date',
        'check_in_time',
        'check_out_time',
        'check_in_method',
        'check_out_method',
        'check_in_location',
        'check_out_location',
        'check_in_photo',
        'check_out_photo',
        'work_duration',
        'break_duration',
        'overtime_duration',
        'late_minutes',
        'early_checkout_minutes',
        'status',
        'notes',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'approved_at' => 'datetime',
        'work_duration' => 'integer',
        'break_duration' => 'integer',
        'overtime_duration' => 'integer',
        'late_minutes' => 'integer',
        'early_checkout_minutes' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\Core\User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function approver()
    {
        return $this->belongsTo(\App\Models\Core\User::class, 'approved_by');
    }
}
