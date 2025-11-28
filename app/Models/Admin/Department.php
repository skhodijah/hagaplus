<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'instansi_id',
        'division_id',
        'name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the instansi that owns the department.
     */
    public function instansi()
    {
        return $this->belongsTo(\App\Models\Instansi::class);
    }

    /**
     * Get the division that owns the department.
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Get the positions for the department.
     */
    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    /**
     * Get the employees for the department.
     */
    public function employees()
    {
        return $this->hasMany(\App\Models\Employee::class);
    }

    /**
     * Scope a query to only include active departments.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
