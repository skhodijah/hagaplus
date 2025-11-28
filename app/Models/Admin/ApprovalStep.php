<?php

namespace App\Models\Admin;

use App\Models\Core\BaseModel;
use App\Models\Core\User;

class ApprovalStep extends BaseModel
{
    protected $table = 'approval_steps';

    protected $fillable = [
        'approval_request_id',
        'approval_level_id',
        'step_order',
        'approver_id',
        'status',
        'notes',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Get the approval request that owns this step
     */
    public function request()
    {
        return $this->belongsTo(ApprovalRequest::class, 'approval_request_id');
    }

    /**
     * Get the approval level for this step
     */
    public function level()
    {
        return $this->belongsTo(ApprovalLevel::class, 'approval_level_id');
    }

    /**
     * Get the approver (user) for this step
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    /**
     * Check if this step is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if this step is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if this step is rejected
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}
