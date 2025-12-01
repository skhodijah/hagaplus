<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all instansi
        $instansis = DB::table('instansis')->get();

        // Default roles to create for each instansi
        $defaultRoles = [
            [
                'name' => 'Employee',
                'system_role_id' => 3, // Employee system role
                'description' => 'Karyawan - Absen, ajukan cuti/izin/lembur, lihat riwayat sendiri',
                'permissions' => ['view-attendance', 'view-leaves', 'view-payroll'],
            ],
            [
                'name' => 'User',
                'system_role_id' => 2, // Admin system role
                'description' => 'Kepala Divisi / Atasan - Approve cuti/izin/lembur bawahan, lihat laporan divisi',
                'permissions' => ['view-employees', 'view-attendance', 'approve-attendance-revisions', 'view-leaves', 'approve-leaves', 'view-payroll', 'view-reports'],
            ],
            [
                'name' => 'HRD',
                'system_role_id' => 2, // Admin system role
                'description' => 'HRD - Kelola karyawan, divisi, kebijakan, rekap absensi, payroll',
                'permissions' => ['view-employees', 'create-employees', 'edit-employees', 'delete-employees', 'view-attendance', 'edit-attendance', 'approve-attendance-revisions', 'view-leaves', 'approve-leaves', 'manage-leave-policies', 'view-payroll', 'process-payroll', 'edit-payroll', 'manage-departments', 'manage-divisions', 'manage-positions', 'manage-employee-policies', 'manage-division-policies', 'view-reports', 'export-reports', 'manage-branches'],
            ],
        ];

        foreach ($instansis as $instansi) {
            foreach ($defaultRoles as $roleData) {
                // Create the role
                $roleId = DB::table('instansi_roles')->insertGetId([
                    'instansi_id' => $instansi->id,
                    'system_role_id' => $roleData['system_role_id'],
                    'name' => $roleData['name'],
                    'description' => $roleData['description'],
                    'is_active' => true,
                    'is_default' => true, // Mark as default role (cannot be deleted)
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Assign permissions to the role
                foreach ($roleData['permissions'] as $permissionSlug) {
                    $permission = DB::table('permissions')->where('slug', $permissionSlug)->first();
                    if ($permission) {
                        DB::table('instansi_role_permissions')->insert([
                            'instansi_role_id' => $roleId,
                            'permission_id' => $permission->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove default roles and their permissions
        DB::table('instansi_roles')->where('is_default', true)->delete();
    }
};
