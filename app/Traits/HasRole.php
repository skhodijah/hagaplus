<?php

namespace App\Traits;

trait HasRole
{
    /**
     * Check if user has specific role
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole($roles)
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        return in_array($this->role, $roles);
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('superadmin');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is employee
     */
    public function isEmployee()
    {
        return $this->hasRole('employee');
    }
}
