<?php

namespace App\Models\Admin;

use App\Models\Core\BaseModel;

class Branch extends BaseModel
{
    protected $fillable = [
        'instansi_id',
        'name',
        'address',
        'phone',
        'email',
        'manager_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function instansi()
    {
        return $this->belongsTo(\App\Models\SuperAdmin\Instansi::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
