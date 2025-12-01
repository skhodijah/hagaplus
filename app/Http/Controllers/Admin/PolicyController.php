<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AttendancePolicy;
use App\Models\DivisionPolicy;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PolicyController extends Controller
{
    /**
     * Display a listing of all policies (Division, Employee, Attendance).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $instansi = $user->instansi;
        $instansiId = $user->instansi_id;

        // 1. Division Policies
        $divisionPolicies = DivisionPolicy::where('instansi_id', $instansiId)
            ->with('division')
            ->orderBy('name')
            ->get();



        // 2.5 Employee Policies (Overrides)
        $employeePolicies = \App\Models\EmployeePolicy::where('instansi_id', $instansiId)
            ->with('employee')
            ->get();
            
        $employees = \App\Models\Admin\Employee::where('employees.instansi_id', $instansiId)
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->orderBy('users.name')
            ->select('employees.*')
            ->with('user')
            ->get();

        // 3. Attendance Policy (Default)
        $attendancePolicy = AttendancePolicy::where('company_id', $instansiId)
            ->where('is_default', true)
            ->first();

        // Determine active tab
        $activeTab = $request->get('tab', 'division_policies');

        return view('admin.policies.index', compact(
            'divisionPolicies',
            'employeePolicies',
            'employees',
            'attendancePolicy',
            'activeTab'
        ));
    }
}
