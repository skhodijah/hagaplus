<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Division;
use App\Models\Admin\InstansiRole;
use App\Models\Admin\SystemRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        $instansiId = Auth::user()->instansi_id;

        // --- Roles Data ---
        $rolesQuery = InstansiRole::where('instansi_id', $instansiId)
            ->with('systemRole');

        if ($request->has('role_search') && !empty($request->role_search)) {
            $search = $request->role_search;
            $rolesQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $roles = $rolesQuery->orderBy('name')->paginate(15, ['*'], 'roles_page');

        // --- Divisions Data ---
        $divisionsQuery = Division::where('instansi_id', $instansiId);

        if ($request->has('division_search') && !empty($request->division_search)) {
            $search = $request->division_search;
            $divisionsQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $divisions = $divisionsQuery->orderBy('name')->paginate(15, ['*'], 'divisions_page');

        // --- Hierarchy Data ---
        $hierarchyDivisions = Division::where('instansi_id', $instansiId)
            ->active()
            ->with(['departments.positions.instansiRole'])
            ->orderBy('name')
            ->get();

        $hierarchyRoles = InstansiRole::where('instansi_id', $instansiId)
            ->active()
            ->orderBy('name')
            ->get();

        $activeTab = $request->get('tab', 'roles');

        // --- System Roles for Modal ---
        $systemRoles = SystemRole::whereIn('slug', ['admin', 'employee'])->get();

        return view('admin.organization.index', compact('roles', 'divisions', 'hierarchyDivisions', 'hierarchyRoles', 'activeTab', 'systemRoles'));
    }
}
