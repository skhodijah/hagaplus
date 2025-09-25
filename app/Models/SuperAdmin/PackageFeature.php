<?php

namespace App\Models\SuperAdmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'feature_id',
        'is_enabled',
        'limits',
        'config_override'
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'limits' => 'array',
        'config_override' => 'array'
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }
}