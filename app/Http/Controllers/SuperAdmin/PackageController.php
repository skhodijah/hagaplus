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
            'max_employees' => 'required|integer|min:1',
            'max_branches' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validatedData['is_active'] = $request->has('is_active');
        $validatedData['features'] = $request->input('features', []);

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
            'max_employees' => 'required|integer|min:1',
            'max_branches' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validatedData['is_active'] = $request->has('is_active');
        $validatedData['features'] = $request->input('features', []);

        $package->update($validatedData);

        return redirect()->route('superadmin.packages.index')->with('success', 'Package berhasil diperbarui.');
    }

    public function destroy(\App\Models\SuperAdmin\Package $package)
    {
        $package->delete();
        return redirect()->route('superadmin.packages.index')->with('success', 'Package berhasil dihapus.');
    }

    /**
     * Show feature configuration page
     */
    public function featureConfiguration()
    {
        $packages = \App\Models\SuperAdmin\Package::all();

        // Since features are now stored as JSON in packages table,
        // we'll show a simplified feature configuration
        return view('superadmin.packages.feature-configuration', compact('packages'));
    }

    /**
     * Update feature configuration for a package
     */
    public function updateFeatureConfiguration(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'features' => 'nullable|array',
            'features.*' => 'string',
        ]);

        $packageId = $validated['package_id'];
        $package = \App\Models\SuperAdmin\Package::findOrFail($packageId);

        // Process checkbox inputs - only include features that are checked
        $features = [];
        if (isset($validated['features'])) {
            foreach ($validated['features'] as $featureKey => $value) {
                if ($value == '1') {
                    $features[] = $featureKey;
                }
            }
        }

        $package->update([
            'features' => $features
        ]);

        return redirect()->back()->with('success', 'Feature configuration updated successfully.');
    }

}
