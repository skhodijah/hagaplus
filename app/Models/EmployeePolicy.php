<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Core\User;

class EmployeePolicy extends Model
{
    protected $fillable = [
        'employee_id',
        'instansi_id',
        'name',
        'description',
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
        'effective_from',
        'effective_until',
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
        'effective_from' => 'datetime',
        'effective_until' => 'datetime',
        'work_start_time' => 'datetime',
        'work_end_time' => 'datetime',
    ];

    /**
     * Get the employee that owns the policy.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    /**
     * Get the instansi that owns the policy.
     */
    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class);
    }

    /**
     * Scope a query to only include active policies.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include policies for a specific employee.
     */
    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Scope a query to only include policies for a specific instansi.
     */
    public function scopeForInstansi($query, $instansiId)
    {
        return $query->where('instansi_id', $instansiId);
    }

    /**
     * Scope a query to only include currently effective policies.
     */
    public function scopeCurrentlyEffective($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('effective_from')
                ->orWhere('effective_from', '<=', now());
        })->where(function ($q) {
            $q->whereNull('effective_until')
                ->orWhere('effective_until', '>=', now());
        });
    }

    /**
     * Check if the policy is currently effective.
     */
    public function isCurrentlyEffective(): bool
    {
        $now = now();

        if ($this->effective_from && $this->effective_from->isAfter($now)) {
            return false;
        }

        if ($this->effective_until && $this->effective_until->isBefore($now)) {
            return false;
        }

        return true;
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

        return $this->work_start_time->format('H:i') . ' - ' . $this->work_end_time->format('H:i');
    }
}
