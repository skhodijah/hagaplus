<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DivisionPolicy;
use App\Models\Admin\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DivisionPolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $instansi = $user->instansi;

        $policies = DivisionPolicy::where('instansi_id', $instansi->id)
            ->with('division')
            ->orderBy('name')
            ->get();

        return view('admin.division-policies.index', compact('policies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $divisions = Division::where('instansi_id', $user->instansi_id)
            ->whereDoesntHave('policy')
            ->get();
            
        return view('admin.division-policies.create', compact('divisions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $instansi = $user->instansi;

        $validated = $request->validate([
            'division_id' => [
                'required',
                'exists:divisions,id',
                Rule::unique('division_policies')->where(function ($query) use ($instansi) {
                    return $query->where('instansi_id', $instansi->id);
                }),
            ],
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
        ]);

        $validated['instansi_id'] = $instansi->id;
        $validated['is_active'] = true;

        DivisionPolicy::create($validated);

        return redirect()->route('admin.division-policies.index')
            ->with('success', 'Division Policy created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DivisionPolicy $divisionPolicy)
    {
        $this->authorizeAccess($divisionPolicy);

        return view('admin.division-policies.show', compact('divisionPolicy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DivisionPolicy $divisionPolicy)
    {
        $this->authorizeAccess($divisionPolicy);
        
        // We don't allow changing division_id for now, or maybe we should?
        // Let's assume division is fixed once created for simplicity, or just display it.
        
        return view('admin.division-policies.edit', compact('divisionPolicy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DivisionPolicy $divisionPolicy)
    {
        $this->authorizeAccess($divisionPolicy);

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
            'is_active' => 'boolean',
        ]);

        $divisionPolicy->update($validated);

        return redirect()->route('admin.division-policies.index')
            ->with('success', 'Division Policy updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DivisionPolicy $divisionPolicy)
    {
        $this->authorizeAccess($divisionPolicy);

        $divisionPolicy->delete();

        return redirect()->route('admin.division-policies.index')
            ->with('success', 'Division Policy deleted successfully.');
    }

    /**
     * Authorize that the current user can access this policy.
     */
    private function authorizeAccess(DivisionPolicy $policy)
    {
        $user = Auth::user();

        if ($policy->instansi_id !== $user->instansi_id) {
            abort(403, 'Unauthorized access to policy.');
        }
    }
}
