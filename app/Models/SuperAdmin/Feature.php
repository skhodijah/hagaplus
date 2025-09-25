<?php

namespace App\Models\SuperAdmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'is_active',
        'sort_order',
        'config'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'config' => 'array'
    ];

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_features')
            ->withPivot(['is_enabled', 'limits', 'config_override'])
            ->withTimestamps();
    }

    public function instansiOverrides()
    {
        return $this->hasMany(InstansiFeatureOverride::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}