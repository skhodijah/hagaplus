<?php

namespace App\Models\Admin;

use App\Models\Core\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendancePolicy extends BaseModel
{
    protected $table = 'attendance_policies';

    protected $fillable = [
        'company_id',
        'name',
        'work_days',
        'start_time',
        'end_time',
        'break_duration',
        'late_tolerance',
        'early_checkout_tolerance',
        'overtime_after_minutes',
        'attendance_methods',
        'auto_checkout',
        'auto_checkout_time',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'work_days' => 'array',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'auto_checkout_time' => 'datetime',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Check if the given time is within the late tolerance period
     */
    public function isLate(\DateTimeInterface $checkInTime): bool
    {
        $startTime = $this->start_time->copy();
        $lateUntil = $startTime->addMinutes($this->late_tolerance);
        
        return $checkInTime > $lateUntil;
    }

    /**
     * Get the late duration in minutes
     */
    public function getLateDuration(\DateTimeInterface $checkInTime): int
    {
        if (!$this->isLate($checkInTime)) {
            return 0;
        }

        $startTime = $this->start_time->copy();
        return $startTime->diffInMinutes($checkInTime) - $this->late_tolerance;
    }
}
