<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class SystemRole extends Model
{
    protected $table = 'system_roles';

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Get the users with this system role
     */
    public function users()
    {
        return $this->hasMany(\App\Models\Core\User::class, 'system_role_id');
    }

    /**
     * Get the instansi roles with this system role
     */
    public function instansiRoles()
    {
        return $this->hasMany(InstansiRole::class, 'system_role_id');
    }
}
