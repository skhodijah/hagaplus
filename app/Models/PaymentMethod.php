<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'type',
        'account_number',
        'account_name',
        'bank_name',
        'qris_image',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Scope for active payment methods
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get the QRIS image URL
    public function getQrisImageUrlAttribute()
    {
        return $this->qris_image ? asset('storage/' . $this->qris_image) : null;
    }

    // Get display name for the payment method
    public function getDisplayNameAttribute()
    {
        if ($this->type === 'qris') {
            return $this->name . ' (QRIS)';
        } elseif ($this->type === 'bank_transfer') {
            return $this->name . ' - ' . $this->bank_name;
        }

        return $this->name;
    }
}
