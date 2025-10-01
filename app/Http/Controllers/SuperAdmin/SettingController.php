<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    /**
     * Display a listing of settings.
     */
    public function index(Request $request): View
    {
        $query = Setting::query();

        // Filter by group
        if ($request->filled('group')) {
            $query->where('group', $request->group);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Search in key or description
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('key', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $settings = $query->orderBy('group')->orderBy('key')->paginate(25);

        // Get unique groups and types for filter dropdowns
        $groups = Setting::distinct('group')->pluck('group');
        $types = Setting::distinct('type')->pluck('type');

        return view('superadmin.system.settings.index', compact('settings', 'groups', 'types'));
    }

    /**
     * Show the form for creating a new setting.
     */
    public function create(): View
    {
        return view('superadmin.system.settings.create');
    }

    /**
     * Store a newly created setting in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'key' => 'required|string|max:255|unique:system_settings,key',
            'value' => 'nullable',
            'type' => 'required|in:string,integer,boolean,float,json',
            'group' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_public' => 'boolean',
        ]);

        Setting::create([
            'key' => $request->key,
            'value' => $request->value,
            'type' => $request->type,
            'group' => $request->group,
            'description' => $request->description,
            'is_public' => $request->boolean('is_public', false),
        ]);

        return redirect()->route('superadmin.system.settings.index')
            ->with('success', 'Setting created successfully.');
    }

    /**
     * Display the specified setting.
     */
    public function show(Setting $setting): View
    {
        return view('superadmin.system.settings.show', compact('setting'));
    }

    /**
     * Show the form for editing the specified setting.
     */
    public function edit(Setting $setting): View
    {
        return view('superadmin.system.settings.edit', compact('setting'));
    }

    /**
     * Update the specified setting in storage.
     */
    public function update(Request $request, Setting $setting): RedirectResponse
    {
        $request->validate([
            'key' => ['required', 'string', 'max:255', Rule::unique('system_settings')->ignore($setting->id)],
            'value' => 'nullable',
            'type' => 'required|in:string,integer,boolean,float,json',
            'group' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_public' => 'boolean',
        ]);

        $setting->update([
            'key' => $request->key,
            'value' => $request->value,
            'type' => $request->type,
            'group' => $request->group,
            'description' => $request->description,
            'is_public' => $request->boolean('is_public', false),
        ]);

        return redirect()->route('superadmin.system.settings.index')
            ->with('success', 'Setting updated successfully.');
    }

    /**
     * Remove the specified setting from storage.
     */
    public function destroy(Setting $setting): RedirectResponse
    {
        $setting->delete();

        return redirect()->route('superadmin.system.settings.index')
            ->with('success', 'Setting deleted successfully.');
    }

    /**
     * Bulk update settings.
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*.id' => 'required|integer|exists:system_settings,id',
            'settings.*.value' => 'nullable',
        ]);

        foreach ($request->settings as $settingData) {
            $setting = Setting::find($settingData['id']);
            if ($setting) {
                $setting->update(['value' => $settingData['value']]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully.'
        ]);
    }

    /**
     * Get setting value via AJAX.
     */
    public function getValue(Request $request): JsonResponse
    {
        $request->validate([
            'key' => 'required|string',
        ]);

        $value = Setting::get($request->key);

        return response()->json([
            'success' => true,
            'value' => $value,
        ]);
    }

    /**
     * Set setting value via AJAX.
     */
    public function setValue(Request $request): JsonResponse
    {
        $request->validate([
            'key' => 'required|string',
            'value' => 'nullable',
            'type' => 'required|in:string,integer,boolean,float,json',
            'group' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $setting = Setting::set(
            $request->key,
            $request->value,
            $request->type,
            $request->group,
            $request->description
        );

        return response()->json([
            'success' => true,
            'message' => 'Setting saved successfully.',
            'setting' => $setting,
        ]);
    }

    /**
     * Export settings to JSON.
     */
    public function export(Request $request)
    {
        $query = Setting::query();

        if ($request->filled('group')) {
            $query->where('group', $request->group);
        }

        $settings = $query->get();

        $filename = 'settings-export-' . now()->format('Y-m-d-H-i-s') . '.json';

        $headers = [
            'Content-Type' => 'application/json',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $data = $settings->map(function ($setting) {
            return [
                'key' => $setting->key,
                'value' => $setting->value,
                'type' => $setting->type,
                'group' => $setting->group,
                'description' => $setting->description,
                'is_public' => $setting->is_public,
            ];
        });

        return response()->json($data, 200, $headers);
    }

    /**
     * Import settings from JSON.
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:json|max:2048',
        ]);

        $file = $request->file('file');
        $data = json_decode(file_get_contents($file->getRealPath()), true);

        if (!$data || !is_array($data)) {
            return back()->withErrors(['file' => 'Invalid JSON file format.']);
        }

        $imported = 0;
        foreach ($data as $settingData) {
            if (isset($settingData['key'])) {
                Setting::set(
                    $settingData['key'],
                    $settingData['value'] ?? null,
                    $settingData['type'] ?? 'string',
                    $settingData['group'] ?? 'general',
                    $settingData['description'] ?? null
                );
                $imported++;
            }
        }

        return redirect()->route('superadmin.system.settings.index')
            ->with('success', "Imported {$imported} settings successfully.");
    }
}
