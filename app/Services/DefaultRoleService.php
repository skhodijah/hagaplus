<?php

namespace App\Services;

use App\Models\Admin\InstansiRole;
use App\Models\Admin\Permission;
use Illuminate\Support\Facades\DB;

class DefaultRoleService
{
    /**
     * Create default roles for an instansi
     * 3 Simple Roles: Employee, User (Approver), HRD
     */
    public static function createDefaultRoles(int $instansiId): void
    {
        $defaultRoles = self::getDefaultRolesData();

        foreach ($defaultRoles as $roleData) {
            // Determine system_role_id based on role name
            // Employee = 3, User & HRD = 2 (Admin)
            $systemRoleId = ($roleData['name'] === 'Employee') ? 3 : 2;
            
            // Create the role
            $role = InstansiRole::create([
                'instansi_id' => $instansiId,
                'system_role_id' => $systemRoleId,
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
     * Simplified to 3 roles only
     */
    private static function getDefaultRolesData(): array
    {
        return [
            // 1. EMPLOYEE - Karyawan Biasa
            [
                'name' => 'Employee',
                'description' => 'Karyawan - Absen, ajukan cuti/izin/lembur, lihat riwayat sendiri',
                'permissions' => [
                    'view-attendance',      // Lihat absensi sendiri
                    'view-leaves',          // Lihat cuti sendiri
                    'view-payroll',         // Lihat slip gaji sendiri
                ],
            ],
            
            // 2. USER - Kepala Divisi / Atasan Langsung / Approver
            [
                'name' => 'User',
                'description' => 'Kepala Divisi / Atasan - Approve cuti/izin/lembur bawahan, lihat laporan divisi',
                'permissions' => [
                    'view-employees',               // Lihat data bawahan
                    'view-attendance',              // Lihat absensi
                    'approve-attendance-revisions', // Approve absen manual/revisi
                    'view-leaves',                  // Lihat pengajuan cuti
                    'approve-leaves',               // Approve/reject cuti/izin/lembur
                    'view-payroll',                 // Lihat payroll
                    'view-reports',                 // Lihat laporan divisi
                ],
            ],
            
            // 3. HRD - Human Resource Department
            [
                'name' => 'HRD',
                'description' => 'HRD - Kelola karyawan, divisi, kebijakan, rekap absensi, payroll',
                'permissions' => [
                    // Employee Management
                    'view-employees',
                    'create-employees',
                    'edit-employees',
                    'delete-employees',
                    
                    // Attendance Management
                    'view-attendance',
                    'edit-attendance',
                    'approve-attendance-revisions',
                    
                    // Leave Management
                    'view-leaves',
                    'approve-leaves',
                    'manage-leave-policies',
                    
                    // Payroll
                    'view-payroll',
                    'process-payroll',
                    'edit-payroll',
                    
                    // Organization Structure
                    'manage-departments',
                    'manage-divisions',
                    'manage-positions',
                    'manage-branches',
                    
                    // Policies & Settings
                    'manage-employee-policies',
                    'manage-division-policies',
                    
                    // Reports
                    'view-reports',
                    'export-reports',
                ],
            ],
        ];
    }
    
    /**
     * Update existing instansi roles to new simplified structure
     * This will be used for migration
     */
    public static function migrateToSimplifiedRoles(int $instansiId): void
    {
        DB::transaction(function () use ($instansiId) {
            // Get existing roles
            $existingRoles = InstansiRole::where('instansi_id', $instansiId)->get();
            
            // Map old roles to new roles
            $roleMapping = [
                'Employee' => 'Employee',
                'Approver' => 'User',
                'Supervisor' => 'User',
                'Manager' => 'User',
                'HR' => 'HRD',
                'Finance' => 'HRD',
            ];
            
            // Delete old non-default roles first
            InstansiRole::where('instansi_id', $instansiId)
                ->where('is_default', false)
                ->delete();
            
            // Delete all default roles
            InstansiRole::where('instansi_id', $instansiId)
                ->where('is_default', true)
                ->delete();
            
            // Create new simplified roles
            self::createDefaultRoles($instansiId);
        });
    }
}
