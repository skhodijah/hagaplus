<?php

namespace App\Models\SuperAdmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionAddon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'billing_cycle',
        'features',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array',
        'is_active' => 'boolean'
    ];

    public function instansis()
    {
        return $this->belongsToMany(Instansi::class, 'instansi_subscription_addons')
            ->withPivot(['price_override', 'active_from', 'active_until', 'is_active'])
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFormattedPrice(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}