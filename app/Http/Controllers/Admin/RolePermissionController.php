<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\InstansiRole;
use App\Models\Admin\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolePermissionController extends Controller
{
    /**
     * Show the form for managing role permissions.
     */
    public function edit(InstansiRole $role)
    {
        // Ensure role belongs to current admin's instansi
        if ($role->instansi_id !== Auth::user()->instansi_id) {
            abort(403);
        }

        // Get all permissions grouped by category
        $permissions = Permission::all()->groupBy('group');
        
        // Get current role permissions
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.permissions', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the role permissions.
     */
    public function update(Request $request, InstansiRole $role)
    {
        // Ensure role belongs to current admin's instansi
        if ($role->instansi_id !== Auth::user()->instansi_id) {
            abort(403);
        }

        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Sync permissions
        $role->permissions()->sync($request->input('permissions', []));

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role permissions updated successfully.');
    }
}
