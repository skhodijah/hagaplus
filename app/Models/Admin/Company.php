<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Package;

class Company extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'logo',
        'package_id',
        'subscription_start',
        'subscription_end',
        'is_active',
        'max_employees',
        'max_branches',
        'settings',
    ];

    protected $casts = [
        'subscription_start' => 'datetime',
        'subscription_end' => 'datetime',
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
