<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuperAdmin\Package;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'BASIC',
                'description' => 'Cocok untuk UMKM / bisnis kecil',
                'price' => 99000,
                'duration_days' => 30,
                'max_employees' => 20,
                'max_admins' => 1,
                'max_branches' => 1,
                'features' => [
                    'Absensi selfie + GPS radius',
                    'Izin, cuti, sakit',
                    'Rekap absensi harian/bulanan',
                    'Slip gaji sederhana (manual input)',
                    'Profil karyawan',
                    '1 Admin Instansi'
                ],
                'permissions' => [
                    'attendance_basic',
                    'leave_management',
                    'payroll_basic',
                    'employee_profile'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'STANDARD',
                'description' => 'Cocok untuk perusahaan kecil–menengah',
                'price' => 199000,
                'duration_days' => 30,
                'max_employees' => 50,
                'max_admins' => 3,
                'max_branches' => 1,
                'features' => [
                    'Semua fitur Basic',
                    'Approval berjenjang (Employee → Supervisor → Manager)',
                    'Pengelolaan lembur',
                    'Reimbursement (pengajuan + approval)',
                    'Slip gaji otomatis',
                    'Payroll dasar (pendapatan & potongan manual)',
                    'Export laporan Excel/PDF',
                    '3 Admin Instansi'
                ],
                'permissions' => [
                    'attendance_basic',
                    'leave_management',
                    'payroll_basic',
                    'employee_profile',
                    'approval_workflow',
                    'overtime_management',
                    'reimbursement',
                    'export_reports'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'PROFESSIONAL',
                'description' => 'Untuk perusahaan menengah–besar',
                'price' => 499000,
                'duration_days' => 30,
                'max_employees' => 200,
                'max_admins' => 10,
                'max_branches' => 5,
                'features' => [
                    'Semua fitur Standard',
                    'Payroll otomatis penuh',
                    'BPJS otomatis',
                    'PPh21 otomatis',
                    'THR',
                    'Lembur auto',
                    'SPT 1721-A1 auto generate',
                    'Multi-role (HR, Finance, Approver)',
                    'Manajemen struktur organisasi',
                    'Audit log',
                    'Import karyawan massal',
                    'API Basic',
                    '10 Admin Instansi'
                ],
                'permissions' => [
                    'attendance_basic',
                    'leave_management',
                    'payroll_auto',
                    'employee_profile',
                    'approval_workflow',
                    'overtime_management',
                    'reimbursement',
                    'export_reports',
                    'bpjs_calculation',
                    'pph21_calculation',
                    'thr_calculation',
                    'multi_role',
                    'audit_log',
                    'bulk_import',
                    'api_basic'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'ENTERPRISE',
                'description' => 'Untuk perusahaan besar / group',
                'price' => 2000000,
                'duration_days' => 30,
                'max_employees' => 10000,
                'max_admins' => 100,
                'max_branches' => 100,
                'features' => [
                    'Semua fitur Professional',
                    'Tanpa batas karyawan',
                    'Multi-cabang / multi-instansi dalam 1 grup',
                    'API Full Access',
                    'SSO (Google/Microsoft)',
                    'Access Control (role custom)',
                    'SLA support & account manager',
                    'Custom report / BI dashboard',
                    'Fitur khusus sesuai permintaan',
                    'Backup & retention premium'
                ],
                'permissions' => [
                    'attendance_basic', // Changed from attendance_advanced since system only supports selfie
                    'leave_management',
                    'payroll_auto',
                    'employee_profile',
                    'approval_workflow',
                    'overtime_management',
                    'reimbursement',
                    'export_reports',
                    'bpjs_calculation',
                    'pph21_calculation',
                    'thr_calculation',
                    'multi_role',
                    'audit_log',
                    'bulk_import',
                    'api_full',
                    'multi_branch',
                    'sso_login',
                    'custom_roles',
                    'advanced_reports'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'TRIAL',
                'description' => 'Free Trial 14 Hari',
                'price' => 0,
                'duration_days' => 14,
                'max_employees' => 10,
                'max_admins' => 1,
                'max_branches' => 1,
                'features' => [
                    'Absensi selfie + GPS radius',
                    'Izin/cuti/sakit + approval',
                    'Rekap absensi',
                    'Profil karyawan',
                    'Struktur organisasi dasar',
                    '1 Admin Instansi',
                    'Max 10 karyawan',
                    'Slip gaji manual',
                    'Reimburse dasar',
                    'Notifikasi dasar'
                ],
                'permissions' => [
                    'attendance_basic',
                    'leave_management',
                    'employee_profile',
                    'basic_organization',
                    'manual_payroll',
                    'basic_reimbursement'
                ],
                'is_active' => true,
            ],
        ];

        foreach ($packages as $package) {
            Package::updateOrCreate(
                ['name' => $package['name']],
                $package
            );
        }
    }
}
