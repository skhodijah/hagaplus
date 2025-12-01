<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\InstansiRole;
use App\Models\Admin\SystemRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        return redirect()->route('admin.organization.index', ['tab' => 'roles']);
    }

    public function create()
    {
        $systemRoles = SystemRole::whereIn('slug', ['admin', 'employee'])->get();
        return view('admin.roles.create', compact('systemRoles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('instansi_roles')->where(function ($query) {
                    return $query->where('instansi_id', Auth::user()->instansi_id);
                }),
            ],
            'description' => 'nullable|string',
            'system_role_id' => 'required|exists:system_roles,id',
            'is_active' => 'boolean',
        ]);

        InstansiRole::create([
            'instansi_id' => Auth::user()->instansi_id,
            'name' => $request->name,
            'system_role_id' => $request->system_role_id,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.organization.index', ['tab' => 'roles'])
            ->with('success', 'Role created successfully.');
    }

    public function edit(InstansiRole $role)
    {
        // Ensure role belongs to current admin's instansi
        if ($role->instansi_id !== Auth::user()->instansi_id) {
            abort(403);
        }

        // Prevent editing default roles
        if ($role->is_default) {
            return redirect()->route('admin.organization.index', ['tab' => 'roles'])
                ->with('error', 'Cannot edit default system roles. You can only view their permissions.');
        }

        $systemRoles = SystemRole::whereIn('slug', ['admin', 'employee'])->get();
        return view('admin.roles.edit', compact('role', 'systemRoles'));
    }

    public function update(Request $request, InstansiRole $role)
    {
        // Ensure role belongs to current admin's instansi
        if ($role->instansi_id !== Auth::user()->instansi_id) {
            abort(403);
        }

        // Prevent updating default roles
        if ($role->is_default) {
            return redirect()->route('admin.organization.index', ['tab' => 'roles'])
                ->with('error', 'Cannot update default system roles.');
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('instansi_roles')->where(function ($query) {
                    return $query->where('instansi_id', Auth::user()->instansi_id);
                })->ignore($role->id),
            ],
            'description' => 'nullable|string',
            'system_role_id' => 'required|exists:system_roles,id',
            'is_active' => 'boolean',
        ]);

        $role->update([
            'name' => $request->name,
            'system_role_id' => $request->system_role_id,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.organization.index', ['tab' => 'roles'])
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(InstansiRole $role)
    {
        // Ensure role belongs to current admin's instansi
        if ($role->instansi_id !== Auth::user()->instansi_id) {
            abort(403);
        }

        // Prevent deleting default roles
        if ($role->is_default) {
            return redirect()->route('admin.organization.index', ['tab' => 'roles'])
                ->with('error', 'Cannot delete default system roles.');
        }

        // Check if role is being used by employees
        if ($role->employees()->count() > 0) {
            return redirect()->route('admin.organization.index', ['tab' => 'roles'])
                ->with('error', 'Cannot delete role that is assigned to employees.');
        }

        $role->delete();

        return redirect()->route('admin.organization.index', ['tab' => 'roles'])
            ->with('success', 'Role deleted successfully.');
    }
}
