<?php

namespace App\Models\Admin;

use App\Models\Core\BaseModel;

class Attendance extends BaseModel
{
    protected $fillable = [
        'employee_id',
        'check_in',
        'check_out',
        'status',
        'notes',
        'location',
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
