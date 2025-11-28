<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Department;
use App\Models\Admin\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'division_id' => 'required|exists:divisions,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Verify division belongs to user's instansi
        $division = Division::findOrFail($request->division_id);
        if ($division->instansi_id !== Auth::user()->instansi_id) {
            abort(403);
        }

        Department::create([
            'instansi_id' => Auth::user()->instansi_id,
            'division_id' => $request->division_id,
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Department created successfully.');
    }

    public function update(Request $request, Department $department)
    {
        if ($department->instansi_id !== Auth::user()->instansi_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $department->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        if ($department->instansi_id !== Auth::user()->instansi_id) {
            abort(403);
        }

        // Check if has employees or positions
        if ($department->employees()->exists() || $department->positions()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete department that has employees or positions.');
        }

        $department->delete();

        return redirect()->back()->with('success', 'Department deleted successfully.');
    }
}
