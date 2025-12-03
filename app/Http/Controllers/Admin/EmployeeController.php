<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Employee;
use App\Models\Admin\Division;
use App\Models\Admin\InstansiRole;
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
        $instansiId = Auth::user()->instansi_id;
        
        $query = Employee::with(['user', 'branch', 'instansi', 'division', 'instansiRole', 'department', 'position'])
            ->where('instansi_id', $instansiId);

        // Filter by branch for non-superadmin users (Admin and Employee roles)
        $user = Auth::user();
        $user->load('employee'); // Explicitly load the employee relationship
        
        if ($user->system_role_id !== 1 && $user->employee && $user->employee->branch_id) {
            $query->where('branch_id', $user->employee->branch_id);
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('employee_id', 'like', "%{$search}%")
                  ->orWhereHas('position', function($posQuery) use ($search) {
                      $posQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('department', function($deptQuery) use ($search) {
                      $deptQuery->where('name', 'like', "%{$search}%");
                  })
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

        // Role filter
        if ($request->has('role_id') && !empty($request->role_id)) {
            $query->where('instansi_role_id', $request->role_id);
        }

        $employees = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get unique departments for filter dropdown
        $departments = \App\Models\Admin\Department::where('instansi_id', $instansiId)
            ->orderBy('name')
            ->get();

        // Get roles for filter dropdown
        $roles = \App\Models\Admin\InstansiRole::where('instansi_id', $instansiId)
            ->orderBy('name')
            ->get();

        // Check setup requirements
        $setupChecks = [
            'attendance_policy' => \App\Models\Admin\AttendancePolicy::where('company_id', $instansiId)
                ->where('is_default', true)
                ->exists(),
            'has_divisions' => \App\Models\Admin\Division::where('instansi_id', $instansiId)->exists(),
            'has_departments' => \App\Models\Admin\Department::where('instansi_id', $instansiId)->exists(),
            'has_positions' => \App\Models\Admin\Position::where('instansi_id', $instansiId)->exists(),
        ];

        $canCreateEmployee = $setupChecks['attendance_policy'] && 
                            $setupChecks['has_divisions'] && 
                            $setupChecks['has_departments'] && 
                            $setupChecks['has_positions'];

        return view('admin.employees.index', compact('employees', 'departments', 'roles', 'setupChecks', 'canCreateEmployee'));
    }

    public function create()
    {
        $divisions = Division::where('instansi_id', Auth::user()->instansi_id)
            ->active()
            ->orderBy('name')
            ->get();
        
        $departments = \App\Models\Admin\Department::where('instansi_id', Auth::user()->instansi_id)
            ->active()
            ->orderBy('name')
            ->get();
        
        // Fetch all active positions regardless of role
        $positions = \App\Models\Admin\Position::where('instansi_id', Auth::user()->instansi_id)
            ->active()
            ->orderBy('name')
            ->get();

        // Check for branch restriction
        $user = Auth::user();
        $branchId = null;
        if ($user->system_role_id !== 1 && $user->employee && $user->employee->branch_id) {
            $branchId = $user->employee->branch_id;
        }

        // Fetch potential supervisors/managers (all employees in the same instansi)
        $supervisorsQuery = Employee::where('instansi_id', Auth::user()->instansi_id)
            ->whereHas('user', function($q) {
                $q->where('system_role_id', 2);
            })
            ->with(['user', 'division', 'department', 'instansiRole']);
            
        if ($branchId) {
            $supervisorsQuery->where('branch_id', $branchId);
        }
        
        $potentialSupervisors = $supervisorsQuery->get()->sortBy('user.name');

        $potentialManagers = $potentialSupervisors; // Same pool for now
        
        // Fetch branches with filtering
        $branchesQuery = \App\Models\Admin\Branch::where('company_id', Auth::user()->instansi_id)
            ->where('is_active', true)
            ->orderBy('name');
            
        if ($branchId) {
            $branchesQuery->where('id', $branchId);
        }
        
        $branches = $branchesQuery->get();
        
        return view('admin.employees.create', compact('divisions', 'departments', 'positions', 'potentialSupervisors', 'potentialManagers', 'branches'));
    }

    public function store(Request $request)
    {
        // Check subscription limits
        $subscriptionService = new \App\Services\SubscriptionService(Auth::user()->instansi);
        if (!$subscriptionService->canAddEmployee()) {
            return back()->with('error', 'Anda telah mencapai batas maksimal jumlah karyawan untuk paket langganan Anda. Silakan upgrade paket untuk menambah karyawan.');
        }

        $request->validate([
            // User info
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            // Employment
            'employee_id' => 'required|string|max:255|unique:employees,employee_id',
            'division_id' => 'nullable|exists:divisions,id',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'branch_id' => 'nullable|exists:branches,id',
            'manager_id' => 'nullable|exists:employees,id',
            'status' => 'required|in:active,inactive,terminated',
            'status_karyawan' => 'required|in:tetap,kontrak,probation,magang',
            'hire_date' => 'required|date',
            'grade_level' => 'nullable|string|max:50',
            // Personal (Employee fills this)
            'nik' => 'nullable|string|max:20|unique:employees,nik',
            'npwp' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'tempat_lahir' => 'nullable|string|max:100',
            'gender' => 'nullable|in:male,female',
            'status_perkawinan' => 'nullable|in:lajang,menikah,cerai',
            'jumlah_tanggungan' => 'nullable|integer|min:0',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string', // Domisili
            'alamat_ktp' => 'nullable|string',
            // Payroll
            'salary' => 'required|numeric|min:0',
            'tunjangan_jabatan' => 'nullable|numeric|min:0',
            'tunjangan_transport' => 'nullable|numeric|min:0',
            'tunjangan_makan' => 'nullable|numeric|min:0',
            'tunjangan_hadir' => 'nullable|numeric|min:0',
            // Bank (Employee fills this)
            'bank_name' => 'nullable|string|max:50',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_holder' => 'nullable|string|max:100',
            'ptkp_status' => 'required|string|in:TK/0,TK/1,TK/2,TK/3,K/0,K/1,K/2,K/3',
            'metode_pajak' => 'required|in:gross,nett,gross_up',
            // BPJS
            'bpjs_kesehatan_number' => 'nullable|string|max:20',
            'bpjs_tk_number' => 'nullable|string|max:20',
            'bpjs_jp_aktif' => 'boolean',
            // Files
            'foto_karyawan' => 'nullable|image|max:2048',
            'scan_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'scan_npwp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'scan_kk' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'bpjs_kesehatan_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'bpjs_tk_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Generate default password
        $firstName = explode(' ', trim($request->name))[0];
        $defaultPassword = strtolower($firstName) . '#' . $request->employee_id;

        DB::transaction(function () use ($request, $defaultPassword) {
            // Get role from position
            $position = \App\Models\Admin\Position::find($request->position_id);
            $instansiRoleId = $position ? $position->instansi_role_id : null;
            
            // Get system role
            $instansiRole = \App\Models\Admin\InstansiRole::find($instansiRoleId);
            $systemRoleId = $instansiRole ? $instansiRole->system_role_id : 3; // Default to Employee

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($defaultPassword),
                'system_role_id' => $systemRoleId,
                'instansi_id' => Auth::user()->instansi_id,
                'phone' => $request->phone,
            ]);

            // Handle file uploads
            $filePaths = [];
            $files = ['foto_karyawan', 'scan_ktp', 'scan_npwp', 'scan_kk', 'bpjs_kesehatan_card', 'bpjs_tk_card'];
            
            foreach ($files as $file) {
                if ($request->hasFile($file)) {
                    $filePaths[$file] = $request->file($file)->store('employee-documents', 'public');
                }
            }

            // Get default attendance policy for this instansi
            $defaultPolicy = \App\Models\Admin\AttendancePolicy::where('company_id', Auth::user()->instansi_id)
                ->where('is_default', true)
                ->first();

            // Create employee
            Employee::create(array_merge([
                'user_id' => $user->id,
                'instansi_id' => Auth::user()->instansi_id,
                'branch_id' => $request->branch_id,
                'division_id' => $request->division_id,
                'instansi_role_id' => $instansiRoleId,
                'employee_id' => $request->employee_id,
                'position_id' => $request->position_id,
                'department_id' => $request->department_id,
                'manager_id' => $request->manager_id,
                'attendance_policy_id' => $defaultPolicy ? $defaultPolicy->id : null, // Auto-assign default policy
                // Employment
                'status' => $request->status,
                'status_karyawan' => $request->status_karyawan,
                'hire_date' => $request->hire_date,
                'grade_level' => $request->grade_level,
                // Personal
                'nik' => $request->nik,
                'npwp' => $request->npwp,
                'date_of_birth' => $request->date_of_birth,
                'tempat_lahir' => $request->tempat_lahir,
                'gender' => $request->gender,
                'status_perkawinan' => $request->status_perkawinan,
                'jumlah_tanggungan' => $request->jumlah_tanggungan,
                'phone' => $request->phone,
                'address' => $request->address,
                'alamat_ktp' => $request->alamat_ktp,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_relation' => $request->emergency_contact_relation,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                // Payroll
                'salary' => $request->salary,
                'tunjangan_jabatan' => $request->tunjangan_jabatan ?? 0,
                'tunjangan_transport' => $request->tunjangan_transport ?? 0,
                'tunjangan_makan' => $request->tunjangan_makan ?? 0,
                'tunjangan_hadir' => $request->tunjangan_hadir ?? 0,
                'bank_name' => $request->bank_name,
                'bank_account_number' => $request->bank_account_number,
                'bank_account_holder' => $request->bank_account_holder,
                'ptkp_status' => $request->ptkp_status,
                'metode_pajak' => $request->metode_pajak,
                // BPJS
                'bpjs_kesehatan_number' => $request->bpjs_kesehatan_number,
                'bpjs_kesehatan_faskes' => $request->bpjs_kesehatan_faskes,
                'bpjs_kesehatan_start_date' => $request->bpjs_kesehatan_start_date,
                'bpjs_kesehatan_tanggungan' => $request->bpjs_kesehatan_tanggungan ?? 0,
                'bpjs_tk_number' => $request->bpjs_tk_number,
                'bpjs_jp_aktif' => $request->has('bpjs_jp_aktif'),
                'bpjs_jkk_rate' => $request->bpjs_jkk_rate ?? 0.24,
                'bpjs_tk_start_date' => $request->bpjs_tk_start_date,
                'catatan_hr' => $request->catatan_hr,
            ], $filePaths));
        });

        return redirect()->route('admin.employees.index')
            ->with('success', "Employee created successfully. Default password: <strong>{$defaultPassword}</strong>");
    }

    public function show(Employee $employee)
    {
        // Ensure employee belongs to current admin's instansi
        if ($employee->instansi_id !== Auth::user()->instansi_id) {
            abort(403);
        }

        $employee->load([
            'user.attendances' => function($query) {
                $query->latest()->take(10);
            },
            'branch',
            'instansi',
            'policy',
            'division.policy',
            'division',
            'department',
            'manager',
        ]);

        return view('admin.employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        // Ensure employee belongs to current admin's instansi
        if ($employee->instansi_id !== Auth::user()->instansi_id) {
            abort(403);
        }

        $divisions = Division::where('instansi_id', Auth::user()->instansi_id)
            ->active()
            ->orderBy('name')
            ->get();
        
        $roles = \App\Models\Admin\InstansiRole::where('instansi_id', Auth::user()->instansi_id)
            ->active()
            ->orderBy('name')
            ->get();
        
        $departments = \App\Models\Admin\Department::where('instansi_id', Auth::user()->instansi_id)
            ->orderBy('name')
            ->get();
        
        $positions = \App\Models\Admin\Position::where('instansi_id', Auth::user()->instansi_id)
            ->orderBy('name')
            ->get();

        // Check for branch restriction
        $user = Auth::user();
        $branchId = null;
        if ($user->system_role_id !== 1 && $user->employee && $user->employee->branch_id) {
            $branchId = $user->employee->branch_id;
        }

        // Fetch all potential supervisors (all employees in instansi except self)
        $supervisorsQuery = Employee::where('instansi_id', Auth::user()->instansi_id)
            ->where('id', '!=', $employee->id)
            ->whereHas('user', function($q) {
                $q->where('system_role_id', 2);
            })
            ->with(['user', 'division', 'department', 'instansiRole']);

        if ($branchId) {
            $supervisorsQuery->where('branch_id', $branchId);
        }
        
        $potentialSupervisors = $supervisorsQuery->get()->sortBy('user.name');

        $potentialManagers = $potentialSupervisors; // Same pool

        // Fetch branches with filtering
        $branchesQuery = \App\Models\Admin\Branch::where('company_id', Auth::user()->instansi_id)
            ->where('is_active', true)
            ->orderBy('name');
            
        if ($branchId) {
            $branchesQuery->where('id', $branchId);
        }
        
        $branches = $branchesQuery->get();

        return view('admin.employees.edit', compact('employee', 'divisions', 'roles', 'departments', 'positions', 'potentialSupervisors', 'potentialManagers', 'branches'));
    }

    public function update(Request $request, Employee $employee)
    {
        if ($employee->instansi_id !== Auth::user()->instansi_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($employee->user_id)],
            'employee_id' => ['required', 'string', 'max:255', Rule::unique('employees')->ignore($employee->id)],
            'division_id' => 'nullable|exists:divisions,id',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'branch_id' => 'nullable|exists:branches,id',
            'manager_id' => 'nullable|exists:employees,id',
            'status' => 'required|in:active,inactive,terminated',
            'status_karyawan' => 'required|in:tetap,kontrak,probation,magang',
            'hire_date' => 'required|date',
            'nik' => ['nullable', 'string', 'max:20', Rule::unique('employees')->ignore($employee->id)],
            'salary' => 'required|numeric|min:0',
            // Add other validations as needed, similar to store but with ignore rules where applicable
            'bpjs_kesehatan_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'bpjs_tk_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        DB::transaction(function () use ($request, $employee) {
            // Get role from position
            $position = \App\Models\Admin\Position::find($request->position_id);
            $instansiRoleId = $position ? $position->instansi_role_id : null;
            
            // Get system role
            $instansiRole = \App\Models\Admin\InstansiRole::find($instansiRoleId);
            $systemRoleId = $instansiRole ? $instansiRole->system_role_id : 3; // Default to Employee

            $employee->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'system_role_id' => $systemRoleId,
            ]);

            // Handle file uploads
            $filePaths = [];
            $files = ['foto_karyawan', 'scan_ktp', 'scan_npwp', 'scan_kk', 'bpjs_kesehatan_card', 'bpjs_tk_card'];
            
            foreach ($files as $file) {
                if ($request->hasFile($file)) {
                    // Delete old file if exists
                    if ($employee->$file) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($employee->$file);
                    }
                    $filePaths[$file] = $request->file($file)->store('employee-documents', 'public');
                }
            }

            // Update employee
            $employee->update(array_merge([
                'instansi_role_id' => $instansiRoleId,
                'branch_id' => $request->branch_id,
                'division_id' => $request->division_id,
                'manager_id' => $request->manager_id,
                'employee_id' => $request->employee_id,
                'position_id' => $request->position_id,
                'department_id' => $request->department_id,
                // Employment
                'status' => $request->status,
                'status_karyawan' => $request->status_karyawan,
                'hire_date' => $request->hire_date,
                'tanggal_berhenti' => $request->tanggal_berhenti,
                'grade_level' => $request->grade_level,
                // Personal
                'nik' => $request->nik,
                'npwp' => $request->npwp,
                'date_of_birth' => $request->date_of_birth,
                'tempat_lahir' => $request->tempat_lahir,
                'gender' => $request->gender,
                'status_perkawinan' => $request->status_perkawinan,
                'jumlah_tanggungan' => $request->jumlah_tanggungan,
                'phone' => $request->phone,
                'address' => $request->address,
                'alamat_ktp' => $request->alamat_ktp,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_relation' => $request->emergency_contact_relation,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                // Payroll
                'salary' => $request->salary,
                'tunjangan_jabatan' => $request->tunjangan_jabatan ?? 0,
                'tunjangan_transport' => $request->tunjangan_transport ?? 0,
                'tunjangan_makan' => $request->tunjangan_makan ?? 0,
                'tunjangan_hadir' => $request->tunjangan_hadir ?? 0,
                'bank_name' => $request->bank_name,
                'bank_account_number' => $request->bank_account_number,
                'bank_account_holder' => $request->bank_account_holder,
                'ptkp_status' => $request->ptkp_status,
                'metode_pajak' => $request->metode_pajak,
                // BPJS
                'bpjs_kesehatan_number' => $request->bpjs_kesehatan_number,
                'bpjs_kesehatan_faskes' => $request->bpjs_kesehatan_faskes,
                'bpjs_kesehatan_start_date' => $request->bpjs_kesehatan_start_date,
                'bpjs_kesehatan_tanggungan' => $request->bpjs_kesehatan_tanggungan ?? 0,
                'bpjs_tk_number' => $request->bpjs_tk_number,
                'bpjs_jp_aktif' => $request->has('bpjs_jp_aktif'),
                'bpjs_jkk_rate' => $request->bpjs_jkk_rate ?? 0.24,
                'bpjs_tk_start_date' => $request->bpjs_tk_start_date,
                'catatan_hr' => $request->catatan_hr,
            ], $filePaths));
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