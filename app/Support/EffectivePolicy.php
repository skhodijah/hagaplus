<?php

namespace App\Support;

use Illuminate\Support\Str;

class EffectivePolicy
{
    protected $attributes = [];

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function __get($key)
    {
        // Check for accessor
        $method = 'get' . Str::studly($key) . 'Attribute';
        if (method_exists($this, $method)) {
            return $this->$method();
        }

        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }
        return null;
    }

    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * Get the work days as a formatted string.
     */
    public function getFormattedWorkDaysAttribute(): string
    {
        if (empty($this->attributes['work_days'])) {
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

        return collect($this->attributes['work_days'])->map(function ($day) use ($days) {
            return $days[$day] ?? ucfirst($day);
        })->join(', ');
    }

    /**
     * Get the work schedule as a formatted string.
     */
    public function getFormattedScheduleAttribute(): string
    {
        if (empty($this->attributes['work_start_time']) || empty($this->attributes['work_end_time'])) {
            return 'Not set';
        }

        return date('H:i', strtotime($this->attributes['work_start_time'])) . ' - ' . date('H:i', strtotime($this->attributes['work_end_time']));
    }
}
