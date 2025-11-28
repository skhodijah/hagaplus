<?php

namespace App\Services;

use App\Models\Admin\InstansiRole;
use App\Models\Admin\Permission;
use Illuminate\Support\Facades\DB;

class DefaultRoleService
{
    /**
     * Create default roles for an instansi
     */
    public static function createDefaultRoles(int $instansiId): void
    {
        $defaultRoles = self::getDefaultRolesData();

        foreach ($defaultRoles as $roleData) {
            // Create the role
            $role = InstansiRole::create([
                'instansi_id' => $instansiId,
                'system_role_id' => 3, // Employee system role
                'name' => $roleData['name'],
                'description' => $roleData['description'],
                'is_active' => true,
                'is_default' => true, // Mark as default role (cannot be deleted)
            ]);

            // Assign permissions to the role
            $permissionIds = Permission::whereIn('slug', $roleData['permissions'])->pluck('id');
            $role->permissions()->sync($permissionIds);
        }
    }

    /**
     * Get default roles data
     */
    private static function getDefaultRolesData(): array
    {
        return [
            [
                'name' => 'Employee',
                'description' => 'Peran standar karyawan',
                'permissions' => ['view-attendance', 'view-leaves', 'view-payroll'],
            ],
            [
                'name' => 'Approver',
                'description' => 'Menyetujui izin/cuti/lembur',
                'permissions' => ['view-employees', 'view-attendance', 'approve-attendance-revisions', 'view-leaves', 'approve-leaves', 'view-payroll'],
            ],
            [
                'name' => 'Supervisor',
                'description' => 'Monitoring dan approval operasional',
                'permissions' => ['view-employees', 'view-attendance', 'edit-attendance', 'approve-attendance-revisions', 'view-leaves', 'approve-leaves', 'view-payroll', 'view-reports'],
            ],
            [
                'name' => 'Manager',
                'description' => 'Pengambil keputusan tingkat tinggi',
                'permissions' => ['view-employees', 'create-employees', 'edit-employees', 'view-attendance', 'edit-attendance', 'approve-attendance-revisions', 'view-leaves', 'approve-leaves', 'manage-leave-policies', 'view-payroll', 'view-reports', 'export-reports', 'manage-employee-policies', 'manage-division-policies'],
            ],
            [
                'name' => 'HR',
                'description' => 'Kelola data karyawan, departemen, kebijakan absensi',
                'permissions' => ['view-employees', 'create-employees', 'edit-employees', 'delete-employees', 'view-attendance', 'edit-attendance', 'approve-attendance-revisions', 'view-leaves', 'approve-leaves', 'manage-leave-policies', 'view-payroll', 'manage-departments', 'manage-divisions', 'manage-positions', 'manage-employee-policies', 'manage-division-policies', 'view-reports', 'export-reports', 'manage-branches'],
            ],
            [
                'name' => 'Finance',
                'description' => 'Penggajian/keuangan',
                'permissions' => ['view-employees', 'view-attendance', 'view-leaves', 'view-payroll', 'process-payroll', 'edit-payroll', 'view-reports', 'export-reports'],
            ],
        ];
    }
}
