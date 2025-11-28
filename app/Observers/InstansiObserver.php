<?php

namespace App\Observers;

use App\Models\SuperAdmin\Instansi;
use App\Services\DefaultRoleService;

class InstansiObserver
{
    /**
     * Handle the Instansi "created" event.
     */
    public function created(Instansi $instansi): void
    {
        // Create default roles for the new instansi
        DefaultRoleService::createDefaultRoles($instansi->id);
    }
}
