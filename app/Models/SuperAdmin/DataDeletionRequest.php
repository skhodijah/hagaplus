<?php

namespace App\Models\SuperAdmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataDeletionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'instansi_id',
        'requested_by',
        'request_type',
        'data_specification',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'scheduled_deletion_date',
        'completed_at',
        'admin_notes'
    ];

    protected $casts = [
        'data_specification' => 'array',
        'approved_at' => 'datetime',
        'scheduled_deletion_date' => 'date',
        'completed_at' => 'datetime'
    ];

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(\App\Models\Core\User::class, 'requested_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(\App\Models\Core\User::class, 'approved_by');
    }

    public function scopeByInstansi($query, $instansiId)
    {
        return $query->where('instansi_id', $instansiId);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeScheduledForToday($query)
    {
        return $query->where('scheduled_deletion_date', now()->toDateString())
            ->where('status', 'approved');
    }

    public function isDue(): bool
    {
        return $this->status === 'approved' &&
               $this->scheduled_deletion_date->isToday();
    }
}