<?php

namespace App\Models\SuperAdmin;

use App\Models\Core\BaseModel;

class Package extends BaseModel
{
    protected $table = 'packages';

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_days',
        'features',
        'permissions',
        'max_employees',
        'max_admins',
        'max_branches',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array',
        'permissions' => 'array',
        'is_active' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function packageChangeRequests()
    {
        return $this->hasMany(PackageChangeRequest::class, 'current_package_id');
    }

    public function requestedPackageChangeRequests()
    {
        return $this->hasMany(PackageChangeRequest::class, 'requested_package_id');
    }

    public function fromTransitions()
    {
        return $this->hasMany(SubscriptionTransition::class, 'from_package_id');
    }

    public function toTransitions()
    {
        return $this->hasMany(SubscriptionTransition::class, 'to_package_id');
    }


    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'discounts', 'applicable_packages');
    }
}
