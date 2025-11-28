<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HierarchyController extends Controller
{
    /**
     * Display the organization hierarchy.
     */
    public function index()
    {
        $instansiId = Auth::user()->instansi_id;

        $divisions = Division::where('instansi_id', $instansiId)
            ->active()
            ->with(['departments.positions.instansiRole'])
            ->orderBy('name')
            ->get();

        $roles = \App\Models\Admin\InstansiRole::where('instansi_id', $instansiId)
            ->active()
            ->orderBy('name')
            ->get();

        return view('admin.hierarchy.index', compact('divisions', 'roles'));
    }
}
