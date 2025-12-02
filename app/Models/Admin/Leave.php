<?php

namespace App\Models\Admin;

use App\Models\Core\BaseModel;

class Leave extends BaseModel
{
    protected $table = 'leaves';

    protected $fillable = [
        'user_id',
        'approval_request_id',
        'leave_type',
        'start_date',
        'end_date',
        'days_count',
        'reason',
        'attachment',
        'status',
        'approved_by',
        'approved_at',
        'supervisor_id',
        'supervisor_approved_at',
        'supervisor_note',
        'hrd_id',
        'hrd_approved_at',
        'hrd_note',
        'rejected_reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'supervisor_approved_at' => 'datetime',
        'hrd_approved_at' => 'datetime',
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

    public function supervisor()
    {
        return $this->belongsTo(\App\Models\Admin\Employee::class, 'supervisor_id');
    }

    public function hrd()
    {
        return $this->belongsTo(\App\Models\Core\User::class, 'hrd_id');
    }

    /**
     * Get the approval request for this leave
     */
    public function approvalRequest()
    {
        return $this->morphOne(ApprovalRequest::class, 'approvable');
    }
    /**
     * Called when the approval request is fully approved.
     */
    public function onApprovalComplete()
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);
    }

    /**
     * Called when the approval request is rejected.
     */
    public function onApprovalRejected(string $reason)
    {
        $this->update([
            'status' => 'rejected',
            'rejected_reason' => $reason,
        ]);
    }
}
