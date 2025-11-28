<?php

namespace App\Models\Admin;

use App\Models\Core\BaseModel;
use App\Models\DivisionPolicy;
use App\Models\Admin\Employee;
use App\Models\Admin\Department;
use App\Models\Admin\Position;

class Division extends BaseModel
{
    protected $table = 'divisions';

    protected $fillable = [
        'instansi_id',
        'name',
        'code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the instansi that owns the division
     */
    public function instansi()
    {
        return $this->belongsTo(\App\Models\SuperAdmin\Instansi::class);
    }

    /**
     * Get the employees for the division
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    /**
     * Get the policy associated with this division
     */
    public function policy()
    {
        return $this->hasOne(DivisionPolicy::class, 'division_id');
    }

    /**
     * Scope a query to only include active divisions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include divisions for a specific instansi
     */
    public function scopeForInstansi($query, $instansiId)
    {
        return $query->where('instansi_id', $instansiId);
    }

    /**
     * Get the next employee number for this division
     */
    public function getNextEmployeeNumber()
    {
        $lastEmployee = Employee::where('division_id', $this->id)
            ->where('employee_id', 'like', $this->code . '%')
            ->orderBy('employee_id', 'desc')
            ->first();

        if (! $lastEmployee) {
            return 1;
        }

        // Extract the number from the employee_id (e.g., CS001 -> 001)
        $lastNumber = (int) substr($lastEmployee->employee_id, strlen($this->code));
        return $lastNumber + 1;
    }

    /**
     * Generate the next employee ID for this division
     */
    public function generateNextEmployeeId()
    {
        $nextNumber = $this->getNextEmployeeNumber();
        return $this->code . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
