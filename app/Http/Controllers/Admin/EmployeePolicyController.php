<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeePolicy;
use App\Models\Core\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeePolicyController extends Controller
{
    /**
     * Display a listing of employee policies.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $instansi = $user->instansi;

        $query = EmployeePolicy::with(['employee'])
            ->where('instansi_id', $instansi->id);

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by employee
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Search by policy name or employee name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                        $employeeQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $policies = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get employees for filter dropdown
        $employees = User::where('instansi_id', $instansi->id)
            ->where('role', 'employee')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('admin.employee-policies.index', compact('policies', 'employees'));
    }

    /**
     * Show the form for creating a new employee policy.
     */
    public function create()
    {
        $user = auth()->user();
        $instansi = $user->instansi;

        // Get employees without existing active policies
        $employees = User::where('instansi_id', $instansi->id)
            ->where('role', 'employee')
            ->whereDoesntHave('employeePolicy', function ($query) {
                $query->where('is_active', true);
            })
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return view('admin.employee-policies.create', compact('employees'));
    }

    /**
     * Store a newly created employee policy.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $instansi = $user->instansi;

        $validated = $request->validate([
            'employee_id' => 'required|exists:users,id',
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
            'effective_from' => 'nullable|date',
            'effective_until' => 'nullable|date|after:effective_from',
        ]);

        // Ensure the employee belongs to the current instansi
        $employee = User::where('id', $validated['employee_id'])
            ->where('instansi_id', $instansi->id)
            ->firstOrFail();

        // Check if employee already has an active policy
        $existingPolicy = EmployeePolicy::where('employee_id', $validated['employee_id'])
            ->where('is_active', true)
            ->first();

        if ($existingPolicy) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['employee_id' => 'This employee already has an active policy.']);
        }

        $validated['instansi_id'] = $instansi->id;
        $validated['is_active'] = true;

        // Set default values if not provided
        $defaults = [
            'work_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            'work_start_time' => '08:00',
            'work_end_time' => '17:00',
            'work_hours_per_day' => 8,
            'grace_period_minutes' => 15,
            'max_late_minutes' => 120,
            'early_leave_grace_minutes' => 15,
            'allow_overtime' => false,
            'max_overtime_hours_per_day' => 2,
            'max_overtime_hours_per_week' => 10,
            'annual_leave_days' => 12,
            'sick_leave_days' => 14,
            'personal_leave_days' => 3,
            'allow_negative_leave_balance' => false,
            'can_work_from_home' => false,
            'flexible_hours' => false,
            'skip_weekends' => false,
            'skip_holidays' => true,
            'require_location_check' => true,
            'allowed_radius_meters' => 100,
        ];

        $validated = array_merge($defaults, $validated);

        EmployeePolicy::create($validated);

        return redirect()->route('admin.employee-policies.index')
            ->with('success', 'Employee policy created successfully.');
    }

    /**
     * Display the specified employee policy.
     */
    public function show(EmployeePolicy $employeePolicy)
    {
        $this->authorizePolicyAccess($employeePolicy);

        return view('admin.employee-policies.show', compact('employeePolicy'));
    }

    /**
     * Show the form for editing the specified employee policy.
     */
    public function edit(EmployeePolicy $employeePolicy)
    {
        $this->authorizePolicyAccess($employeePolicy);

        return view('admin.employee-policies.edit', compact('employeePolicy'));
    }

    /**
     * Update the specified employee policy.
     */
    public function update(Request $request, EmployeePolicy $employeePolicy)
    {
        $this->authorizePolicyAccess($employeePolicy);

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
            'effective_from' => 'nullable|date',
            'effective_until' => 'nullable|date|after:effective_from',
        ]);

        $employeePolicy->update($validated);

        return redirect()->route('admin.employee-policies.index')
            ->with('success', 'Employee policy updated successfully.');
    }

    /**
     * Remove the specified employee policy.
     */
    public function destroy(EmployeePolicy $employeePolicy)
    {
        $this->authorizePolicyAccess($employeePolicy);

        $employeePolicy->delete();

        return redirect()->route('admin.employee-policies.index')
            ->with('success', 'Employee policy deleted successfully.');
    }

    /**
     * Toggle policy active status.
     */
    public function toggleStatus(EmployeePolicy $employeePolicy)
    {
        $this->authorizePolicyAccess($employeePolicy);

        $employeePolicy->update(['is_active' => !$employeePolicy->is_active]);

        $status = $employeePolicy->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "Employee policy {$status} successfully.");
    }

    /**
     * Authorize that the current user can access this policy.
     */
    private function authorizePolicyAccess(EmployeePolicy $employeePolicy)
    {
        $user = auth()->user();

        if ($employeePolicy->instansi_id !== $user->instansi_id) {
            abort(403, 'Unauthorized access to employee policy.');
        }
    }
}
