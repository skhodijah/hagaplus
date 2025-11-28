<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Position;
use App\Models\Admin\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PositionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'instansi_role_id' => 'required|exists:instansi_roles,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Verify department belongs to user's instansi
        $department = Department::findOrFail($request->department_id);
        if ($department->instansi_id !== Auth::user()->instansi_id) {
            abort(403);
        }

        Position::create([
            'instansi_id' => Auth::user()->instansi_id,
            'division_id' => $department->division_id,
            'department_id' => $request->department_id,
            'instansi_role_id' => $request->instansi_role_id,
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Position created successfully.');
    }

    public function update(Request $request, Position $position)
    {
        if ($position->instansi_id !== Auth::user()->instansi_id) {
            abort(403);
        }

        $request->validate([
            'instansi_role_id' => 'required|exists:instansi_roles,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $position->update([
            'instansi_role_id' => $request->instansi_role_id,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Position updated successfully.');
    }

    public function destroy(Position $position)
    {
        if ($position->instansi_id !== Auth::user()->instansi_id) {
            abort(403);
        }

        // Check if has employees
        if ($position->employees()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete position that is assigned to employees.');
        }

        $position->delete();

        return redirect()->back()->with('success', 'Position deleted successfully.');
    }
}
