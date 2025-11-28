<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DivisionController extends Controller
{
    public function index(Request $request)
    {
        $query = Division::where('instansi_id', Auth::user()->instansi_id);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        $divisions = $query->orderBy('name')->paginate(15);

        return view('admin.divisions.index', compact('divisions'));
    }

    public function create()
    {
        return view('admin.divisions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('divisions')->where(function ($query) {
                    return $query->where('instansi_id', Auth::user()->instansi_id);
                }),
            ],
            'code' => [
                'required',
                'string',
                'max:10',
                'regex:/^[A-Z]+$/',
                Rule::unique('divisions')->where(function ($query) {
                    return $query->where('instansi_id', Auth::user()->instansi_id);
                }),
            ],
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ], [
            'code.regex' => 'Kode divisi harus berupa huruf kapital tanpa spasi atau karakter khusus.',
        ]);

        Division::create([
            'instansi_id' => Auth::user()->instansi_id,
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division created successfully.');
    }

    public function edit(Division $division)
    {
        // Ensure division belongs to current admin's instansi
        if ($division->instansi_id !== Auth::user()->instansi_id) {
            abort(403);
        }

        return view('admin.divisions.edit', compact('division'));
    }

    public function update(Request $request, Division $division)
    {
        // Ensure division belongs to current admin's instansi
        if ($division->instansi_id !== Auth::user()->instansi_id) {
            abort(403);
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('divisions')->where(function ($query) {
                    return $query->where('instansi_id', Auth::user()->instansi_id);
                })->ignore($division->id),
            ],
            'code' => [
                'required',
                'string',
                'max:10',
                'regex:/^[A-Z]+$/',
                Rule::unique('divisions')->where(function ($query) {
                    return $query->where('instansi_id', Auth::user()->instansi_id);
                })->ignore($division->id),
            ],
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ], [
            'code.regex' => 'Kode divisi harus berupa huruf kapital tanpa spasi atau karakter khusus.',
        ]);

        $division->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division updated successfully.');
    }

    public function destroy(Division $division)
    {
        // Ensure division belongs to current admin's instansi
        if ($division->instansi_id !== Auth::user()->instansi_id) {
            abort(403);
        }

        // Check if division is being used by employees
        if ($division->employees()->count() > 0) {
            return redirect()->route('admin.divisions.index')
                ->with('error', 'Cannot delete division that is assigned to employees.');
        }

        $division->delete();

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division deleted successfully.');
    }

    /**
     * Get next employee ID for a division (AJAX endpoint)
     */
    public function getNextEmployeeId(Division $division)
    {
        // Ensure division belongs to current admin's instansi
        if ($division->instansi_id !== Auth::user()->instansi_id) {
            abort(403);
        }

        return response()->json([
            'employee_id' => $division->generateNextEmployeeId(),
        ]);
    }
}
