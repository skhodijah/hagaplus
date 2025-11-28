<?php

namespace App\Models\Admin;

use App\Models\Core\BaseModel;

class Role extends BaseModel
{
    protected $table = 'roles';

    protected $fillable = [
        'instansi_id',
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the instansi that owns the role
     */
    public function instansi()
    {
        return $this->belongsTo(\App\Models\SuperAdmin\Instansi::class);
    }

    /**
     * Get the employees for the role
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Scope a query to only include active roles
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include roles for a specific instansi
     */
    public function scopeForInstansi($query, $instansiId)
    {
        return $query->where('instansi_id', $instansiId);
    }
}
