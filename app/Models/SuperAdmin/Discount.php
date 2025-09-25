<?php

namespace App\Models\SuperAdmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'value',
        'max_discount',
        'usage_limit',
        'usage_limit_per_instansi',
        'used_count',
        'valid_from',
        'valid_until',
        'applicable_packages',
        'target',
        'is_active'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'applicable_packages' => 'array',
        'is_active' => 'boolean'
    ];

    public function usages()
    {
        return $this->hasMany(DiscountUsage::class);
    }

    public function instansis()
    {
        return $this->belongsToMany(Instansi::class, 'discount_usage')
            ->withPivot(['original_amount', 'discount_amount', 'final_amount'])
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeValid($query)
    {
        $now = now()->toDateString();
        return $query->where('valid_from', '<=', $now)
            ->where('valid_until', '>=', $now);
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    public function isValid(): bool
    {
        $now = now()->toDateString();
        return $this->is_active &&
               $this->valid_from <= $now &&
               $this->valid_until >= $now &&
               ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }

    public function calculateDiscount(float $amount): float
    {
        if ($this->type === 'percentage') {
            $discount = ($amount * $this->value) / 100;
            return $this->max_discount ? min($discount, $this->max_discount) : $discount;
        }

        return min($this->value, $amount);
    }

    public function canBeUsedByInstansi(Instansi $instansi): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        $instansiUsageCount = $this->usages()
            ->where('instansi_id', $instansi->id)
            ->count();

        return $instansiUsageCount < $this->usage_limit_per_instansi;
    }

    public function getFormattedValue(): string
    {
        return $this->type === 'percentage'
            ? $this->value . '%'
            : 'Rp ' . number_format($this->value, 0, ',', '.');
    }
}