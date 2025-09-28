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
        $packages = \App\Models\SuperAdmin\Package::with('packageFeatures.feature')->get();
        $features = \App\Models\SuperAdmin\Feature::orderBy('category')->orderBy('sort_order')->get();

        return view('superadmin.packages.feature-configuration', compact('packages', 'features'));
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

        // Delete existing package features
        \App\Models\SuperAdmin\PackageFeature::where('package_id', $packageId)->delete();

        // Process each feature
        if (isset($validated['features'])) {
            foreach ($validated['features'] as $featureId => $featureData) {
                // Check if feature is enabled
                $isEnabled = isset($featureData['enabled']) && $featureData['enabled'] == '1';

                if ($isEnabled) {
                    // Build limits array from form inputs
                    $limits = [];
                    if (isset($featureData['limits'])) {
                        $limits = $featureData['limits'];
                        // Convert checkbox values to boolean
                        foreach ($limits as $key => $value) {
                            if ($value === '1' || $value === '0') {
                                $limits[$key] = $value === '1';
                            }
                        }
                    }

                    // Build config array if present
                    $config = [];
                    if (isset($featureData['config'])) {
                        $config = $featureData['config'];
                    }

                    \App\Models\SuperAdmin\PackageFeature::create([
                        'package_id' => $packageId,
                        'feature_id' => $featureId,
                        'is_enabled' => true,
                        'limits' => !empty($limits) ? $limits : null,
                        'config_override' => !empty($config) ? $config : null,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Feature configuration updated successfully.');
    }

    /**
     * Show pricing settings page
     */
    public function pricingSettings()
    {
        $packages = \App\Models\SuperAdmin\Package::all();
        $discounts = \App\Models\SuperAdmin\Discount::where('is_active', true)->get();

        return view('superadmin.packages.pricing-settings', compact('packages', 'discounts'));
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
