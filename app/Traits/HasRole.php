<?php

namespace App\Traits;

trait HasRole
{
    /**
     * Check if user has specific role (by slug)
     */
    public function hasRole($roleSlug)
    {
        if (!$this->systemRole) {
            return false;
        }

        // Direct match
        if ($this->systemRole->slug === $roleSlug) {
            return true;
        }

        // Hierarchy: Admin can also access employee features
        if ($roleSlug === 'employee' && $this->systemRole->slug === 'admin') {
            return true;
        }

        return false;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole($roles)
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        if (!$this->systemRole) {
            return false;
        }

        return in_array($this->systemRole->slug, $roles);
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

    /**
     * Get the role name for display
     */
    public function getRoleName()
    {
        return $this->systemRole ? $this->systemRole->name : 'Unknown';
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission($permission)
    {
        // Superadmin has all permissions
        if ($this->systemRole && $this->systemRole->slug === 'superadmin') {
            return true;
        }

        // Instansi owner (system role id 2) has full access when not linked to an employee record
        if ($this->system_role_id === 2 && !$this->employee) {
            return true;
        }

        // Check if user has employee record with instansi role
        if ($this->employee && $this->employee->instansiRole) {
            return $this->employee->instansiRole->hasPermission($permission);
        }

        return false;
    }
}
