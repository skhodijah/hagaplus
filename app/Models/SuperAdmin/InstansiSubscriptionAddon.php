<?php

namespace App\Models\SuperAdmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstansiSubscriptionAddon extends Model
{
    use HasFactory;

    protected $fillable = [
        'instansi_id',
        'subscription_addon_id',
        'price_override',
        'active_from',
        'active_until',
        'is_active'
    ];

    protected $casts = [
        'price_override' => 'decimal:2',
        'active_from' => 'date',
        'active_until' => 'date',
        'is_active' => 'boolean'
    ];

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function addon()
    {
        return $this->belongsTo(SubscriptionAddon::class, 'subscription_addon_id');
    }

    public function scopeActive($query)
    {
        $now = now()->toDateString();
        return $query->where('is_active', true)
            ->where('active_from', '<=', $now)
            ->where(function ($q) use ($now) {
                $q->whereNull('active_until')
                  ->orWhere('active_until', '>=', $now);
            });
    }

    public function getEffectivePrice(): float
    {
        return $this->price_override ?? $this->addon->price;
    }
}