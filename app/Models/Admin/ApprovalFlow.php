<?php

namespace App\Models\Admin;

use App\Models\Core\BaseModel;

class ApprovalFlow extends BaseModel
{
    protected $table = 'approval_flows';

    protected $fillable = [
        'instansi_id',
        'name',
        'flow_type',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the instansi that owns the approval flow
     */
    public function instansi()
    {
        return $this->belongsTo(\App\Models\SuperAdmin\Instansi::class);
    }

    /**
     * Get the levels for this approval flow
     */
    public function levels()
    {
        return $this->hasMany(ApprovalLevel::class)->orderBy('level_order');
    }

    /**
     * Get the approval requests for this flow
     */
    public function requests()
    {
        return $this->hasMany(ApprovalRequest::class);
    }

    /**
     * Scope a query to only include active flows
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include flows for a specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('flow_type', $type);
    }
}
