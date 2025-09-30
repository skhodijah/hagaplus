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
            'features' => 'array',
        ]);

        $packageId = $validated['package_id'];
        $package = \App\Models\SuperAdmin\Package::findOrFail($packageId);

        // Since features are now stored as JSON in packages table,
        // we'll store the features directly in the package
        $features = isset($validated['features']) ? $validated['features'] : [];

        $package->update([
            'features' => $features
        ]);

        return redirect()->back()->with('success', 'Feature configuration updated successfully.');
    }

    /**
     * Show pricing settings page
     */
    public function pricingSettings()
    {
        $packages = \App\Models\SuperAdmin\Package::all();

        // Since discounts table was dropped, we'll just show packages
        return view('superadmin.packages.pricing-settings', compact('packages'));
    }

    /**
     * Update pricing settings
     */
    public function updatePricingSettings(Request $request)
    {
        $validated = $request->validate([
            'packages' => 'required|array',
            'packages.*.id' => 'required|exists:packages,id',
            'packages.*.price' => 'required|numeric|min:0',
            'packages.*.duration_days' => 'required|integer|min:1',
            'packages.*.max_employees' => 'required|integer|min:1',
            'packages.*.max_branches' => 'required|integer|min:1',
            'packages.*.is_active' => 'boolean',
        ]);

        foreach ($validated['packages'] as $packageData) {
            \App\Models\SuperAdmin\Package::where('id', $packageData['id'])->update([
                'price' => $packageData['price'],
                'duration_days' => $packageData['duration_days'],
                'max_employees' => $packageData['max_employees'],
                'max_branches' => $packageData['max_branches'],
                'is_active' => $packageData['is_active'] ?? false,
            ]);
        }

        return redirect()->back()->with('success', 'Pricing settings updated successfully.');
    }
}
