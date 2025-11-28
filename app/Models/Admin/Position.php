<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'instansi_id',
        'division_id',
        'department_id',
        'instansi_role_id',
        'name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the instansi that owns the position.
     */
    public function instansi()
    {
        return $this->belongsTo(\App\Models\SuperAdmin\Instansi::class);
    }

    /**
     * Get the division that owns the position.
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Get the department that owns the position.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the instansi role associated with the position.
     */
    public function instansiRole()
    {
        return $this->belongsTo(InstansiRole::class, 'instansi_role_id');
    }

    /**
     * Get the employees for the position.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Scope a query to only include active positions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
