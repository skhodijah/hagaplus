<?php

namespace App\Models\Admin;

use App\Models\Core\BaseModel;

class Branch extends BaseModel
{
    protected $table = 'branches';

    protected $fillable = [
        'company_id',
        'name',
        'address',
        'latitude',
        'longitude',
        'radius',
        'timezone',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'radius' => 'integer',
        'is_active' => 'boolean',
    ];

    public function instansi()
    {
        return $this->belongsTo(\App\Models\SuperAdmin\Instansi::class, 'company_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'branch_id');
    }

    public function attendances()
    {
        return $this->hasMany(\App\Models\Admin\Attendance::class, 'branch_id');
    }
}
