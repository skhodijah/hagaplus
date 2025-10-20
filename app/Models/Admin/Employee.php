<?php

namespace App\Models\Admin;

use App\Models\Core\BaseModel;
use App\Models\Core\User;

class Employee extends BaseModel
{
    protected $table = 'employees';

    protected $fillable = [
        'user_id',
        'instansi_id',
        'branch_id',
        'employee_id',
        'position',
        'department',
        'salary',
        'hire_date',
        'status',
        'attendance_policy_id',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'salary' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function instansi()
    {
        return $this->belongsTo(\App\Models\SuperAdmin\Instansi::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }


    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function attendancePolicy()
    {
        return $this->belongsTo(\App\Models\Admin\AttendancePolicy::class);
    }
}
