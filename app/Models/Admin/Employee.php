<?php

namespace App\Models\Admin;

use App\Models\Core\BaseModel;
use App\Models\Core\User;
use App\Models\EmployeePolicy;

class Employee extends BaseModel
{
    protected $table = 'employees';

    protected $fillable = [
        'user_id',
        'instansi_id',
        'branch_id',
        'division_id',
        'department_id',
        'position_id',
        'manager_id',
        'instansi_role_id',
        'employee_id',
        // Identitas
        'nik',
        'npwp',
        'date_of_birth',
        'tempat_lahir',
        'gender',
        'address', // alamat domisili
        'alamat_ktp',
        'status_perkawinan',
        'jumlah_tanggungan',
        'kewarganegaraan',
        'phone',
        // Kontak darurat
        'emergency_contact_name',
        'emergency_contact_relation',
        'emergency_contact_phone',
        // Pekerjaan
        'status_karyawan',
        'grade_level',
        'hire_date',
        'tanggal_berhenti',
        'status',
        // Payroll - Gaji & Tunjangan
        'salary',
        'tunjangan_jabatan',
        'tunjangan_transport',
        'tunjangan_makan',
        'tunjangan_hadir',
        // Bank
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        // BPJS
        'bpjs_kesehatan_number',
        'bpjs_kesehatan_faskes',
        'bpjs_kesehatan_start_date',
        'bpjs_kesehatan_tanggungan',
        'bpjs_kesehatan_card',
        'bpjs_tk_number',
        'bpjs_jp_aktif',
        'bpjs_jkk_rate',
        'bpjs_tk_start_date',
        'bpjs_tk_card',
        // Pajak
        'ptkp_status',
        'metode_pajak',
        // Dokumen
        'foto_karyawan',
        'scan_ktp',
        'scan_npwp',
        'scan_kk',
        'catatan_hr',
        // Policy
        'attendance_policy_id',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'tanggal_berhenti' => 'date',
        'date_of_birth' => 'date',
        'bpjs_kesehatan_start_date' => 'date',
        'bpjs_tk_start_date' => 'date',
        'salary' => 'decimal:2',
        'tunjangan_jabatan' => 'decimal:2',
        'tunjangan_transport' => 'decimal:2',
        'tunjangan_makan' => 'decimal:2',
        'tunjangan_hadir' => 'decimal:2',
        'bpjs_jkk_rate' => 'decimal:2',
        'jumlah_tanggungan' => 'integer',
        'bpjs_kesehatan_tanggungan' => 'integer',
        'bpjs_jp_aktif' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Manager relationship (direct manager of the employee)
     */
    public function manager()
    {
        return $this->belongsTo(self::class, 'manager_id');
    }

    /**
     * Subordinates relationship (employees managed by this employee)
     */
    public function subordinates()
    {
        return $this->hasMany(self::class, 'manager_id');
    }

    /**
     * Get the approval hierarchy for this employee
     * Returns array of approvers in order: Manager, HR
     */
    public function getApprovalHierarchy()
    {
        $hierarchy = [];
        
        // Level 1: Manager (if exists)
        if ($this->manager_id && $this->manager) {
            $hierarchy[] = [
                'level' => 1,
                'type' => 'manager',
                'name' => 'Manager',
                'employee_id' => $this->manager_id,
                'user_id' => $this->manager->user_id,
                'user_name' => $this->manager->user->name,
            ];
        }
        
        return $hierarchy;
    }



    public function instansi()
    {
        return $this->belongsTo(\App\Models\SuperAdmin\Instansi::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function instansiRole()
    {
        return $this->belongsTo(InstansiRole::class, 'instansi_role_id');
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    /**
     * Get the policy associated with this employee (override)
     */
    public function policy()
    {
        // The employee_policies table uses user_id in its employee_id column
        // So we match employees.user_id with employee_policies.employee_id
        return $this->hasOne(EmployeePolicy::class, 'employee_id', 'user_id');
    }

    public function attendancePolicy()
    {
        return $this->belongsTo(\App\Models\Admin\AttendancePolicy::class);
    }

    /**
     * Get the effective policy for the employee (merging override and division policy)
     */
    /**
     * Get the effective policy for the employee (merging override, division, and default policy)
     */
    public function getEffectivePolicyAttribute()
    {
        $policyFields = [
            'work_days', 'work_start_time', 'work_end_time', 'work_hours_per_day', 'break_times',
            'grace_period_minutes', 'max_late_minutes', 'early_leave_grace_minutes',
            'allow_overtime', 'max_overtime_hours_per_day', 'max_overtime_hours_per_week',
            'annual_leave_days', 'sick_leave_days', 'personal_leave_days', 'allow_negative_leave_balance',
            'can_work_from_home', 'flexible_hours', 'skip_weekends', 'skip_holidays',
            'require_location_check', 'allowed_radius_meters', 'allowed_locations',
            'has_shifts', 'shift_schedule', 'custom_rules'
        ];

        $attributes = [];
        
        // Level 3: Employee Specific Policy (Highest Priority)
        $employeePolicy = $this->policy;
        
        // Level 2: Division Policy
        $divisionPolicy = $this->division ? $this->division->policy : null;
        
        // Level 1: Default Attendance Policy (Lowest Priority)
        $defaultPolicy = $this->attendancePolicy;

        foreach ($policyFields as $field) {
            if ($employeePolicy && !is_null($employeePolicy->$field)) {
                $attributes[$field] = $employeePolicy->$field;
            } elseif ($divisionPolicy && !is_null($divisionPolicy->$field)) {
                $attributes[$field] = $divisionPolicy->$field;
            } elseif ($defaultPolicy) {
                // Map fields from AttendancePolicy to EffectivePolicy structure
                // Note: Field names might differ slightly, need mapping
                switch ($field) {
                    case 'work_start_time':
                        $attributes[$field] = $defaultPolicy->start_time ? $defaultPolicy->start_time->format('H:i:s') : null;
                        break;
                    case 'work_end_time':
                        $attributes[$field] = $defaultPolicy->end_time ? $defaultPolicy->end_time->format('H:i:s') : null;
                        break;
                    case 'grace_period_minutes':
                        $attributes[$field] = $defaultPolicy->late_tolerance;
                        break;
                    case 'early_leave_grace_minutes':
                        $attributes[$field] = $defaultPolicy->early_checkout_tolerance;
                        break;
                    case 'break_times':
                        // Simple break duration to break_times format if needed, or just null for now
                        $attributes[$field] = null; 
                        break;
                    default:
                        $attributes[$field] = $defaultPolicy->$field ?? null;
                }
            } else {
                $attributes[$field] = null;
            }
        }

        // Defaults if still null
        if (is_null($attributes['grace_period_minutes'])) $attributes['grace_period_minutes'] = 15;
        if (is_null($attributes['max_late_minutes'])) $attributes['max_late_minutes'] = 120;
        if (is_null($attributes['work_hours_per_day'])) $attributes['work_hours_per_day'] = 8;
        
        // Ensure work_days is array
        if (isset($attributes['work_days']) && is_string($attributes['work_days'])) {
             $attributes['work_days'] = json_decode($attributes['work_days'], true);
        }
        
        return new \App\Support\EffectivePolicy($attributes);
    }

    /**
     * Check if the employee profile is complete.
     * Required fields: Personal Info, Emergency Contact, Bank Info, Documents.
     */
    public function isProfileComplete()
    {
        $requiredFields = [
            // Personal Info
            'nik',
            'phone',
            'alamat_ktp',
            'date_of_birth',
            'tempat_lahir',
            'gender',
            'status_perkawinan',
            'jumlah_tanggungan',
            
            // Emergency Contact
            'emergency_contact_name',
            'emergency_contact_relation',
            'emergency_contact_phone',
            
            // Bank Info
            'bank_name',
            'bank_account_number',
            'bank_account_holder',
            
            // Documents (Paths)
            'foto_karyawan',
            'scan_ktp',
            // 'scan_npwp', // Optional usually
            'scan_kk',
        ];

        foreach ($requiredFields as $field) {
            if (empty($this->$field)) {
                return false;
            }
        }

        return true;
    }
}
