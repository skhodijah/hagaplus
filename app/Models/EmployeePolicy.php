<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeePolicy extends Model
{
    protected $fillable = [
        'instansi_id',
        'employee_id',
        'name',
        'work_days',
        'work_start_time',
        'work_end_time',
        'work_hours_per_day',
        'break_times',
        'grace_period_minutes',
        'max_late_minutes',
        'early_leave_grace_minutes',
        'allow_overtime',
        'max_overtime_hours_per_day',
        'max_overtime_hours_per_week',
        'annual_leave_days',
        'sick_leave_days',
        'personal_leave_days',
        'allow_negative_leave_balance',
        'can_work_from_home',
        'flexible_hours',
        'skip_weekends',
        'skip_holidays',
        'require_location_check',
        'allowed_radius_meters',
        'allowed_locations',
        'has_shifts',
        'shift_schedule',
        'custom_rules',
        'is_active',
    ];

    protected $casts = [
        'work_days' => 'array',
        'break_times' => 'array',
        'allow_overtime' => 'boolean',
        'allow_negative_leave_balance' => 'boolean',
        'can_work_from_home' => 'boolean',
        'flexible_hours' => 'boolean',
        'skip_weekends' => 'boolean',
        'skip_holidays' => 'boolean',
        'require_location_check' => 'boolean',
        'has_shifts' => 'boolean',
        'is_active' => 'boolean',
        'allowed_radius_meters' => 'decimal:2',
        'allowed_locations' => 'array',
        'shift_schedule' => 'array',
        'custom_rules' => 'array',
    ];

    /**
     * Get the instansi that owns the policy.
     */
    public function instansi(): BelongsTo
    {
        return $this->belongsTo(\App\Models\SuperAdmin\Instansi::class);
    }

    /**
     * Get the employee that owns the policy.
     */
    public function employee(): BelongsTo
    {
        // The employee_id column in this table actually holds the user_id
        // So we relate to Employee model via its user_id column
        return $this->belongsTo(\App\Models\Admin\Employee::class, 'employee_id', 'user_id');
    }

    /**
     * Get the user that owns the policy.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Core\User::class, 'employee_id');
    }

    /**
     * Scope a query to only include active policies.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include policies for a specific instansi.
     */
    public function scopeForInstansi($query, $instansiId)
    {
        return $query->where('instansi_id', $instansiId);
    }

    /**
     * Get the work days as a formatted string.
     */
    public function getFormattedWorkDaysAttribute(): string
    {
        if (!$this->work_days) {
            return 'Not set';
        }

        $days = [
            'monday' => 'Mon',
            'tuesday' => 'Tue',
            'wednesday' => 'Wed',
            'thursday' => 'Thu',
            'friday' => 'Fri',
            'saturday' => 'Sat',
            'sunday' => 'Sun',
        ];

        return collect($this->work_days)->map(function ($day) use ($days) {
            return $days[$day] ?? ucfirst($day);
        })->join(', ');
    }

    /**
     * Get the work schedule as a formatted string.
     */
    public function getFormattedScheduleAttribute(): string
    {
        if (!$this->work_start_time || !$this->work_end_time) {
            return 'Not set';
        }

        return date('H:i', strtotime($this->work_start_time)) . ' - ' . date('H:i', strtotime($this->work_end_time));
    }
}
