<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Employee;
use App\Models\Core\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['user', 'branch', 'instansi'])
            ->where('instansi_id', Auth::user()->instansi_id);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('employee_id', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Department filter
        if ($request->has('department') && !empty($request->department)) {
            $query->where('department', $request->department);
        }

        $employees = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get unique departments for filter dropdown
        $departments = Employee::where('instansi_id', Auth::user()->instansi_id)
            ->distinct()
            ->pluck('department')
            ->filter()
            ->sort();

        return view('admin.employees.index', compact('employees', 'departments'));
    }

    public function create()
    {
        // TODO: Implement create method
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'employee_id' => 'required|string|max:255|unique:employees,employee_id',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'hire_date' => 'required|date',
            'branch_id' => 'nullable|exists:branches,id',
            'status' => 'required|in:active,inactive,terminated',
        ]);

        DB::transaction(function () use ($request) {
            // Create user account
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('password123'), // Default password
                'role' => 'employee',
                'instansi_id' => Auth::user()->instansi_id,
            ]);

            // Create employee record
            Employee::create([
                'user_id' => $user->id,
                'instansi_id' => Auth::user()->instansi_id,
                'branch_id' => $request->branch_id,
                'employee_id' => $request->employee_id,
                'position' => $request->position,
                'department' => $request->department,
                'salary' => $request->salary,
                'hire_date' => $request->hire_date,
                'status' => $request->status,
            ]);
        });

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee)
    {
        // Ensure employee belongs to current admin's instansi
        if ($employee->instansi_id !== Auth::user()->instansi_id) {
            abort(403);
        }

        $employee->load(['user.attendances' => function($query) {
            $query->latest()->take(10);
        }, 'branch', 'instansi']);

        return view('admin.employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        // Ensure employee belongs to current admin's instansi
        if ($employee->instansi_id !== Auth::user()->instansi_id) {
            abort(403);
        }

        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        // Ensure employee belongs to current admin's instansi
        if ($employee->instansi_id !== Auth::user()->instansi_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($employee->user_id)],
            'employee_id' => ['required', 'string', 'max:255', Rule::unique('employees')->ignore($employee->id)],
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'hire_date' => 'required|date',
            'branch_id' => 'nullable|exists:branches,id',
            'status' => 'required|in:active,inactive,terminated',
        ]);

        DB::transaction(function () use ($request, $employee) {
            // Update user account
            $employee->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Update employee record
            $employee->update([
                'branch_id' => $request->branch_id,
                'employee_id' => $request->employee_id,
                'position' => $request->position,
                'department' => $request->department,
                'salary' => $request->salary,
                'hire_date' => $request->hire_date,
                'status' => $request->status,
            ]);
        });

        return redirect()->route('admin.employees.show', $employee)
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        // Ensure employee belongs to current admin's instansi
        if ($employee->instansi_id !== Auth::user()->instansi_id) {
            abort(403);
        }

        DB::transaction(function () use ($employee) {
            // Delete employee record first (due to foreign key constraints)
            $employee->delete();

            // Delete user account
            $employee->user->delete();
        });

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}