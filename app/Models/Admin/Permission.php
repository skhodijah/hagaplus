<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'group',
        'description',
    ];

    /**
     * Get the roles that have this permission.
     */
    public function instansiRoles(): BelongsToMany
    {
        return $this->belongsToMany(InstansiRole::class, 'instansi_role_permissions');
    }
}
