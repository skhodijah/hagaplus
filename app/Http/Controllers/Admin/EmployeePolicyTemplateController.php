<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeePolicyTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeePolicyTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $instansi = $user->instansi;

        $templates = EmployeePolicyTemplate::where('instansi_id', $instansi->id)
            ->orderBy('is_default', 'desc')
            ->orderBy('name')
            ->get();

        return view('admin.employee-policy-templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.employee-policy-templates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $instansi = $user->instansi;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'work_days' => 'nullable|array',
            'work_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'work_start_time' => 'nullable|date_format:H:i',
            'work_end_time' => 'nullable|date_format:H:i',
            'work_hours_per_day' => 'nullable|integer|min:1|max:24',
            'break_times' => 'nullable|array',
            'grace_period_minutes' => 'nullable|integer|min:0|max:120',
            'max_late_minutes' => 'nullable|integer|min:0|max:480',
            'early_leave_grace_minutes' => 'nullable|integer|min:0|max:120',
            'allow_overtime' => 'boolean',
            'max_overtime_hours_per_day' => 'nullable|integer|min:0|max:12',
            'max_overtime_hours_per_week' => 'nullable|integer|min:0|max:60',
            'annual_leave_days' => 'nullable|integer|min:0|max:365',
            'sick_leave_days' => 'nullable|integer|min:0|max:365',
            'personal_leave_days' => 'nullable|integer|min:0|max:365',
            'allow_negative_leave_balance' => 'boolean',
            'can_work_from_home' => 'boolean',
            'flexible_hours' => 'boolean',
            'skip_weekends' => 'boolean',
            'skip_holidays' => 'boolean',
            'require_location_check' => 'boolean',
            'allowed_radius_meters' => 'nullable|numeric|min:0|max:10000',
            'is_default' => 'boolean',
        ]);

        $validated['instansi_id'] = $instansi->id;
        $validated['is_active'] = true;

        // If this is set as default, unset other defaults
        if ($validated['is_default'] ?? false) {
            EmployeePolicyTemplate::where('instansi_id', $instansi->id)
                ->update(['is_default' => false]);
        }

        EmployeePolicyTemplate::create($validated);

        return redirect()->route('admin.employee-policy-templates.index')
            ->with('success', 'Template created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeePolicyTemplate $employeePolicyTemplate)
    {
        $this->authorizeAccess($employeePolicyTemplate);

        return view('admin.employee-policy-templates.show', compact('employeePolicyTemplate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeePolicyTemplate $employeePolicyTemplate)
    {
        $this->authorizeAccess($employeePolicyTemplate);

        return view('admin.employee-policy-templates.edit', compact('employeePolicyTemplate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeePolicyTemplate $employeePolicyTemplate)
    {
        $this->authorizeAccess($employeePolicyTemplate);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'work_days' => 'nullable|array',
            'work_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'work_start_time' => 'nullable|date_format:H:i',
            'work_end_time' => 'nullable|date_format:H:i',
            'work_hours_per_day' => 'nullable|integer|min:1|max:24',
            'break_times' => 'nullable|array',
            'grace_period_minutes' => 'nullable|integer|min:0|max:120',
            'max_late_minutes' => 'nullable|integer|min:0|max:480',
            'early_leave_grace_minutes' => 'nullable|integer|min:0|max:120',
            'allow_overtime' => 'boolean',
            'max_overtime_hours_per_day' => 'nullable|integer|min:0|max:12',
            'max_overtime_hours_per_week' => 'nullable|integer|min:0|max:60',
            'annual_leave_days' => 'nullable|integer|min:0|max:365',
            'sick_leave_days' => 'nullable|integer|min:0|max:365',
            'personal_leave_days' => 'nullable|integer|min:0|max:365',
            'allow_negative_leave_balance' => 'boolean',
            'can_work_from_home' => 'boolean',
            'flexible_hours' => 'boolean',
            'skip_weekends' => 'boolean',
            'skip_holidays' => 'boolean',
            'require_location_check' => 'boolean',
            'allowed_radius_meters' => 'nullable|numeric|min:0|max:10000',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // If this is set as default, unset other defaults
        if ($validated['is_default'] ?? false) {
            EmployeePolicyTemplate::where('instansi_id', $employeePolicyTemplate->instansi_id)
                ->where('id', '!=', $employeePolicyTemplate->id)
                ->update(['is_default' => false]);
        }

        $employeePolicyTemplate->update($validated);

        return redirect()->route('admin.employee-policy-templates.index')
            ->with('success', 'Template updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeePolicyTemplate $employeePolicyTemplate)
    {
        $this->authorizeAccess($employeePolicyTemplate);

        $employeePolicyTemplate->delete();

        return redirect()->route('admin.employee-policy-templates.index')
            ->with('success', 'Template deleted successfully.');
    }

    /**
     * Show form to apply template to employees.
     */
    public function apply()
    {
        $user = Auth::user();
        $instansi = $user->instansi;

        // Get templates
        $templates = EmployeePolicyTemplate::where('instansi_id', $instansi->id)
            ->where('is_active', true)
            ->orderBy('is_default', 'desc')
            ->orderBy('name')
            ->get();

        // Get employees without active policies
        $employees = \App\Models\Core\User::where('instansi_id', $instansi->id)
            ->whereHas('systemRole', function($q) {
                $q->where('slug', 'employee');
            })
            ->whereDoesntHave('employeePolicy', function ($query) {
                $query->where('is_active', true);
            })
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return view('admin.employee-policy-templates.apply', compact('templates', 'employees'));
    }

    /**
     * Authorize that the current user can access this template.
     */
    private function authorizeAccess(EmployeePolicyTemplate $template)
    {
        $user = Auth::user();

        if ($template->instansi_id !== $user->instansi_id) {
            abort(403, 'Unauthorized access to template.');
        }
    }
}
