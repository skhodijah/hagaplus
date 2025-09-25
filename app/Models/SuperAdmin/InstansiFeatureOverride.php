<?php

namespace App\Models\SuperAdmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstansiFeatureOverride extends Model
{
    use HasFactory;

    protected $fillable = [
        'instansi_id',
        'feature_id',
        'is_enabled',
        'custom_limits',
        'custom_config',
        'reason',
        'effective_from',
        'effective_until',
        'applied_by'
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'custom_limits' => 'array',
        'custom_config' => 'array',
        'effective_from' => 'date',
        'effective_until' => 'date'
    ];

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }

    public function appliedBy()
    {
        return $this->belongsTo(\App\Models\Core\User::class, 'applied_by');
    }

    public function scopeActive($query)
    {
        $now = now()->toDateString();
        return $query->where('effective_from', '<=', $now)
            ->where(function ($q) use ($now) {
                $q->whereNull('effective_until')
                  ->orWhere('effective_until', '>=', $now);
            });
    }

    public function scopeByInstansi($query, $instansiId)
    {
        return $query->where('instansi_id', $instansiId);
    }
}