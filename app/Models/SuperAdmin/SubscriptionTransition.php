<?php

namespace App\Models\SuperAdmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionTransition extends Model
{
    use HasFactory;

    protected $fillable = [
        'instansi_id',
        'from_package_id',
        'to_package_id',
        'subscription_id',
        'transition_type',
        'effective_from',
        'effective_until',
        'transition_amount',
        'prorate_credit',
        'feature_changes',
        'notes',
        'processed_by'
    ];

    protected $casts = [
        'effective_from' => 'datetime',
        'effective_until' => 'datetime',
        'transition_amount' => 'decimal:2',
        'prorate_credit' => 'decimal:2',
        'feature_changes' => 'array'
    ];

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function fromPackage()
    {
        return $this->belongsTo(Package::class, 'from_package_id');
    }

    public function toPackage()
    {
        return $this->belongsTo(Package::class, 'to_package_id');
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(\App\Models\Core\User::class, 'processed_by');
    }
}