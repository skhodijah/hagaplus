<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeePolicy;
use App\Models\Admin\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EmployeePolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $instansi = $user->instansi;

        $policies = EmployeePolicy::where('instansi_id', $instansi->id)
            ->with('employee')
            ->get();
            
        $employees = Employee::where('employees.instansi_id', $instansi->id)
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->orderBy('users.name')
            ->select('employees.*')
            ->with('user')
            ->get();

        return view('admin.employee-policies.index', compact('policies', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $employees = Employee::where('instansi_id', $user->instansi_id)
            ->whereDoesntHave('policy')
            ->get();
            
        return view('admin.employee-policies.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $instansi = $user->instansi;

        $validated = $request->validate([
            'employee_id' => [
                'required',
                'exists:employees,id',
                Rule::unique('employee_policies')->where(function ($query) use ($instansi) {
                    return $query->where('instansi_id', $instansi->id);
                }),
            ],
            // All other fields are nullable because they override only what's set
            'work_days' => 'nullable|array',
            'work_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'work_start_time' => 'nullable|date_format:H:i',
            'work_end_time' => 'nullable|date_format:H:i',
            'work_hours_per_day' => 'nullable|integer|min:1|max:24',
            'break_times' => 'nullable|array',
            'grace_period_minutes' => 'nullable|integer|min:0|max:120',
            'max_late_minutes' => 'nullable|integer|min:0|max:480',
            'early_leave_grace_minutes' => 'nullable|integer|min:0|max:120',
            'allow_overtime' => 'nullable|boolean',
            'max_overtime_hours_per_day' => 'nullable|integer|min:0|max:12',
            'max_overtime_hours_per_week' => 'nullable|integer|min:0|max:60',
            'annual_leave_days' => 'nullable|integer|min:0|max:365',
            'sick_leave_days' => 'nullable|integer|min:0|max:365',
            'personal_leave_days' => 'nullable|integer|min:0|max:365',
            'allow_negative_leave_balance' => 'nullable|boolean',
            'can_work_from_home' => 'nullable|boolean',
            'flexible_hours' => 'nullable|boolean',
            'skip_weekends' => 'nullable|boolean',
            'skip_holidays' => 'nullable|boolean',
            'require_location_check' => 'nullable|boolean',
            'allowed_radius_meters' => 'nullable|numeric|min:0|max:10000',
        ]);

        $validated['instansi_id'] = $instansi->id;
        $validated['is_active'] = true;

        EmployeePolicy::create($validated);

        return redirect()->route('admin.employee-policies.index')
            ->with('success', 'Employee Policy created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeePolicy $employeePolicy)
    {
        $this->authorizeAccess($employeePolicy);

        return view('admin.employee-policies.show', compact('employeePolicy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeePolicy $employeePolicy)
    {
        $this->authorizeAccess($employeePolicy);
        
        return view('admin.employee-policies.edit', compact('employeePolicy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeePolicy $employeePolicy)
    {
        $this->authorizeAccess($employeePolicy);

        $validated = $request->validate([
            'work_days' => 'nullable|array',
            'work_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'work_start_time' => 'nullable|date_format:H:i',
            'work_end_time' => 'nullable|date_format:H:i',
            'work_hours_per_day' => 'nullable|integer|min:1|max:24',
            'break_times' => 'nullable|array',
            'grace_period_minutes' => 'nullable|integer|min:0|max:120',
            'max_late_minutes' => 'nullable|integer|min:0|max:480',
            'early_leave_grace_minutes' => 'nullable|integer|min:0|max:120',
            'allow_overtime' => 'nullable|boolean',
            'max_overtime_hours_per_day' => 'nullable|integer|min:0|max:12',
            'max_overtime_hours_per_week' => 'nullable|integer|min:0|max:60',
            'annual_leave_days' => 'nullable|integer|min:0|max:365',
            'sick_leave_days' => 'nullable|integer|min:0|max:365',
            'personal_leave_days' => 'nullable|integer|min:0|max:365',
            'allow_negative_leave_balance' => 'nullable|boolean',
            'can_work_from_home' => 'nullable|boolean',
            'flexible_hours' => 'nullable|boolean',
            'skip_weekends' => 'nullable|boolean',
            'skip_holidays' => 'nullable|boolean',
            'require_location_check' => 'nullable|boolean',
            'allowed_radius_meters' => 'nullable|numeric|min:0|max:10000',
            'is_active' => 'boolean',
        ]);

        $employeePolicy->update($validated);

        return redirect()->route('admin.employee-policies.index')
            ->with('success', 'Employee Policy updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeePolicy $employeePolicy)
    {
        $this->authorizeAccess($employeePolicy);

        $employeePolicy->delete();

        return redirect()->route('admin.employee-policies.index')
            ->with('success', 'Employee Policy deleted successfully.');
    }

    /**
     * Authorize that the current user can access this policy.
     */
    private function authorizeAccess(EmployeePolicy $policy)
    {
        $user = Auth::user();

        if ($policy->instansi_id !== $user->instansi_id) {
            abort(403, 'Unauthorized access to policy.');
        }
    }
}
