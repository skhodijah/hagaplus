<?php

namespace App\Models\SuperAdmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'instansi_id',
        'current_package_id',
        'requested_package_id',
        'type',
        'status',
        'requested_effective_date',
        'prorate_amount',
        'reason',
        'admin_notes',
        'requested_by',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'requested_effective_date' => 'date',
        'prorate_amount' => 'decimal:2',
        'approved_at' => 'datetime'
    ];

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function currentPackage()
    {
        return $this->belongsTo(Package::class, 'current_package_id');
    }

    public function requestedPackage()
    {
        return $this->belongsTo(Package::class, 'requested_package_id');
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

    public function isUpgrade(): bool
    {
        return $this->type === 'upgrade';
    }

    public function isDowngrade(): bool
    {
        return $this->type === 'downgrade';
    }
}