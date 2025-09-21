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
        'features',
        'max_employees',
        'max_branches',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
