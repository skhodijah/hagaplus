<?php

namespace App\Models\SuperAdmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountUsage extends Model
{
    use HasFactory;

    protected $table = 'discount_usage';

    protected $fillable = [
        'discount_id',
        'instansi_id',
        'subscription_id',
        'original_amount',
        'discount_amount',
        'final_amount'
    ];

    protected $casts = [
        'original_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2'
    ];

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function getSavingsPercentage(): float
    {
        if ($this->original_amount == 0) {
            return 0;
        }

        return ($this->discount_amount / $this->original_amount) * 100;
    }
}