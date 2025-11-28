<?php

namespace App\Models\Admin;

use App\Models\Core\BaseModel;

class InstansiRole extends BaseModel
{
    protected $table = 'instansi_roles';

    protected $fillable = [
        'instansi_id',
        'system_role_id',
        'name',
        'description',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    /**
     * Get the permissions for the role
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'instansi_role_permissions');
    }

    /**
     * Check if role has a specific permission
     */
    public function hasPermission(string $permissionSlug): bool
    {
        return $this->permissions()->where('slug', $permissionSlug)->exists();
    }

    /**
     * Check if role has any of the given permissions
     */
    public function hasAnyPermission(array $permissionSlugs): bool
    {
        return $this->permissions()->whereIn('slug', $permissionSlugs)->exists();
    }

    /**
     * Check if role has all of the given permissions
     */
    public function hasAllPermissions(array $permissionSlugs): bool
    {
        $count = $this->permissions()->whereIn('slug', $permissionSlugs)->count();
        return $count === count($permissionSlugs);
    }

    /**
     * Get the instansi that owns the role
     */
    public function instansi()
    {
        return $this->belongsTo(\App\Models\SuperAdmin\Instansi::class);
    }

    /**
     * Get the system role
     */
    public function systemRole()
    {
        return $this->belongsTo(SystemRole::class, 'system_role_id');
    }

    /**
     * Get the employees for the role
     */
    public function employees()
    {
        return $this->hasMany(Employee::class, 'instansi_role_id');
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
