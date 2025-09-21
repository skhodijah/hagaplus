<?php

namespace App\Models\Trait;

trait HasAttendance
{
    public function attendances()
    {
        return $this->morphMany(\App\Models\Admin\Attendance::class, 'attendanceable');
    }

    public function checkIn($data = [])
    {
        return $this->attendances()->create(array_merge([
            'check_in' => now(),
            'status' => 'present',
        ], $data));
    }

    public function checkOut($data = [])
    {
        $attendance = $this->attendances()
            ->whereDate('check_in', today())
            ->whereNull('check_out')
            ->first();

        if ($attendance) {
            $attendance->update(array_merge([
                'check_out' => now(),
            ], $data));
        }

        return $attendance;
    }
}
