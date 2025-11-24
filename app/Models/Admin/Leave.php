<?php

namespace App\Models\Admin;

use App\Models\Core\BaseModel;

class Leave extends BaseModel
{
    protected $table = 'leaves';

    protected $fillable = [
        'user_id',
        'leave_type',
        'start_date',
        'end_date',
        'days_count',
        'reason',
        'attachment',
        'status',
        'approved_by',
        'approved_at',
        'rejected_reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'days_count' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\Core\User::class);
    }

    public function approver()
    {
        return $this->belongsTo(\App\Models\Core\User::class, 'approved_by');
    }
}
