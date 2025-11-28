<?php

namespace App\Models\Admin;

use App\Models\Core\BaseModel;

class ApprovalLevel extends BaseModel
{
    protected $table = 'approval_levels';

    protected $fillable = [
        'approval_flow_id',
        'level_order',
        'level_name',
        'approver_type',
        'instansi_role_id',
        'is_required',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'level_order' => 'integer',
    ];

    /**
     * Get the approval flow that owns this level
     */
    public function flow()
    {
        return $this->belongsTo(ApprovalFlow::class, 'approval_flow_id');
    }

    /**
     * Get the instansi role for this level (if custom_role type)
     */
    public function instansiRole()
    {
        return $this->belongsTo(InstansiRole::class, 'instansi_role_id');
    }

    /**
     * Get all approval steps for this level
     */
    public function steps()
    {
        return $this->hasMany(ApprovalStep::class, 'approval_level_id');
    }
}
