<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = \App\Models\SuperAdmin\Package::latest()->paginate(10);
        return view('superadmin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('superadmin.packages.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
            'max_employees' => 'required|integer|min:1',
            'max_branches' => 'required|integer|min:1',
            'max_admins' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validatedData['is_active'] = $request->has('is_active');
        // Filter out empty features
        $features = $request->input('features', []);
        $validatedData['features'] = array_values(array_filter($features, function($value) {
            return !empty(trim($value));
        }));
        
        // Ensure permissions is array
        $validatedData['permissions'] = $request->input('permissions', []);

        \App\Models\SuperAdmin\Package::create($validatedData);

        return redirect()->route('superadmin.packages.index')->with('success', 'Package berhasil ditambahkan.');
    }

    public function show(\App\Models\SuperAdmin\Package $package)
    {
        return view('superadmin.packages.show', compact('package'));
    }

    public function edit(\App\Models\SuperAdmin\Package $package)
    {
        return view('superadmin.packages.edit', compact('package'));
    }

    public function update(Request $request, \App\Models\SuperAdmin\Package $package)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
            'max_employees' => 'required|integer|min:1',
            'max_branches' => 'required|integer|min:1',
            'max_admins' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validatedData['is_active'] = $request->has('is_active');
        // Filter out empty features
        $features = $request->input('features', []);
        $validatedData['features'] = array_values(array_filter($features, function($value) {
            return !empty(trim($value));
        }));
        
        // Ensure permissions is array
        $validatedData['permissions'] = $request->input('permissions', []);

        $package->update($validatedData);

        return redirect()->route('superadmin.packages.index')->with('success', 'Package berhasil diperbarui.');
    }

    public function destroy(\App\Models\SuperAdmin\Package $package)
    {
        $package->delete();
        return redirect()->route('superadmin.packages.index')->with('success', 'Package berhasil dihapus.');
    }
}
