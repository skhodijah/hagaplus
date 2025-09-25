<?php

namespace App\Models\SuperAdmin;

use App\Models\Core\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends BaseModel
{
    protected $table = 'subscriptions';

    protected $fillable = [
        'instansi_id',
        'package_id',
        'status',
        'start_date',
        'end_date',
        'price',
        'payment_status',
        'payment_method',
        'payment_date',
        'notes',
        'effective_date',
        'trial_ends_at',
        'is_trial',
        'discount_amount',
        'discount_id',
    ];

    protected $dates = [
        'start_date',
        'end_date',
        'payment_date',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'effective_date' => 'date',
        'trial_ends_at' => 'date',
        'is_trial' => 'boolean',
        'discount_amount' => 'decimal:2',
        'payment_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function transitions()
    {
        return $this->hasMany(SubscriptionTransition::class);
    }

    public function discountUsage()
    {
        return $this->hasOne(DiscountUsage::class);
    }

    /**
     * Get the current status based on dates and manual status
     */
    public function getCurrentStatusAttribute()
    {
        // If manually suspended or canceled, keep that status
        if (in_array($this->status, ['suspended', 'canceled'])) {
            return $this->status;
        }

        // Check if expired based on end_date
        if (now()->isAfter($this->end_date)) {
            return 'expired';
        }

        // If within valid dates and not manually set to inactive, it's active
        return $this->status === 'active' ? 'active' : 'inactive';
    }

    /**
     * Check if subscription can be extended (close to expiration)
     */
    public function canBeExtended()
    {
        $thresholdDays = $this->getExtensionThreshold();
        $expirationDate = $this->end_date;
        $thresholdDate = now()->addDays($thresholdDays);

        return now()->isAfter($expirationDate->copy()->subDays($thresholdDays))
            && now()->isBefore($expirationDate);
    }

    /**
     * Get extension threshold days from settings
     */
    private function getExtensionThreshold()
    {
        return \Illuminate\Support\Facades\DB::table('settings')
            ->where('key', 'subscription_extension_threshold_days')
            ->value('value') ?? 3;
    }

    /**
     * Update status based on current date
     */
    public function updateStatusBasedOnDate()
    {
        $currentStatus = $this->getCurrentStatusAttribute();

        if ($this->status !== $currentStatus) {
            $this->update(['status' => $currentStatus]);
        }
    }
}
