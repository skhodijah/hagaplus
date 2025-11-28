<?php

namespace App\Models\Admin;

use App\Models\Core\BaseModel;
use App\Models\Core\User;

class ApprovalRequest extends BaseModel
{
    protected $table = 'approval_requests';

    protected $fillable = [
        'approval_flow_id',
        'approvable_type',
        'approvable_id',
        'requester_id',
        'employee_id',
        'status',
        'current_level',
        'rejection_reason',
        'submitted_at',
        'completed_at',
    ];

    protected $casts = [
        'current_level' => 'integer',
        'submitted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the approval flow for this request
     */
    public function flow()
    {
        return $this->belongsTo(ApprovalFlow::class, 'approval_flow_id');
    }

    /**
     * Get the requester (user) of this approval request
     */
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    /**
     * Get the employee associated with this request
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Get the approvable model (Leave, AttendanceRevision, etc.)
     */
    public function approvable()
    {
        return $this->morphTo();
    }

    /**
     * Get all approval steps for this request
     */
    public function steps()
    {
        return $this->hasMany(ApprovalStep::class, 'approval_request_id')->orderBy('step_order');
    }

    /**
     * Get the current approval step
     */
    public function currentStep()
    {
        return $this->steps()->where('status', 'pending')->orderBy('step_order')->first();
    }

    /**
     * Get completed steps
     */
    public function completedSteps()
    {
        return $this->steps()->whereIn('status', ['approved', 'rejected'])->orderBy('step_order');
    }

    /**
     * Check if request is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if request is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if request is rejected
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Get the progress percentage
     */
    public function getProgressPercentageAttribute()
    {
        $totalSteps = $this->steps()->count();
        if ($totalSteps === 0) return 0;
        
        $completedSteps = $this->steps()->where('status', 'approved')->count();
        return round(($completedSteps / $totalSteps) * 100);
    }

    /**
     * Get current approver(s) for the current step
     */
    public function getCurrentApproversAttribute()
    {
        $currentStep = $this->currentStep();
        if (!$currentStep) return collect();
        
        return collect([$currentStep->approver])->filter();
    }
}
