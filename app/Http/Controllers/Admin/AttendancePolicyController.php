<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AttendancePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendancePolicyController extends Controller
{
    public function index()
    {
        $instansiId = Auth::user()->instansi_id;
        
        // Get the default attendance policy for this instansi
        $policy = AttendancePolicy::where('company_id', $instansiId)
            ->where('is_default', true)
            ->first();
        
        return view('admin.attendance-policy.index', compact('policy'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'work_days' => 'required|array|min:1',
            'work_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'break_duration' => 'nullable|integer|min:0|max:480',
            'late_tolerance' => 'nullable|integer|min:0|max:120',
            'early_checkout_tolerance' => 'nullable|integer|min:0|max:120',
            'overtime_after_minutes' => 'nullable|integer|min:0',
            'auto_checkout' => 'boolean',
            'auto_checkout_time' => 'nullable|date_format:H:i',
        ]);

        $instansiId = Auth::user()->instansi_id;

        // Delete existing default policy if any
        AttendancePolicy::where('company_id', $instansiId)
            ->where('is_default', true)
            ->delete();

        // Create new policy
        $policy = AttendancePolicy::create([
            'company_id' => $instansiId,
            'name' => 'Default Policy',
            'work_days' => $validated['work_days'],
            'start_time' => today()->setTimeFromTimeString($validated['start_time']),
            'end_time' => today()->setTimeFromTimeString($validated['end_time']),
            'break_duration' => $validated['break_duration'] ?? 0,
            'late_tolerance' => $validated['late_tolerance'] ?? 0,
            'early_checkout_tolerance' => $validated['early_checkout_tolerance'] ?? 0,
            'overtime_after_minutes' => $validated['overtime_after_minutes'] ?? 0,
            'attendance_methods' => ['gps', 'selfie'], // Default methods as array
            'auto_checkout' => $validated['auto_checkout'] ?? false,
            'auto_checkout_time' => isset($validated['auto_checkout_time']) 
                ? today()->setTimeFromTimeString($validated['auto_checkout_time']) 
                : null,
            'is_default' => true,
            'is_active' => true,
        ]);

        // Auto-assign this policy to all employees in this instansi
        \App\Models\Admin\Employee::where('instansi_id', $instansiId)
            ->update(['attendance_policy_id' => $policy->id]);

        return redirect()->route('admin.attendance-policy.index')
            ->with('success', 'Kebijakan absensi berhasil disimpan dan diterapkan ke semua karyawan.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'work_days' => 'required|array|min:1',
            'work_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'break_duration' => 'nullable|integer|min:0|max:480',
            'late_tolerance' => 'nullable|integer|min:0|max:120',
            'early_checkout_tolerance' => 'nullable|integer|min:0|max:120',
            'overtime_after_minutes' => 'nullable|integer|min:0',
            'auto_checkout' => 'boolean',
            'auto_checkout_time' => 'nullable|date_format:H:i',
        ]);

        $instansiId = Auth::user()->instansi_id;

        $policy = AttendancePolicy::where('company_id', $instansiId)
            ->where('is_default', true)
            ->firstOrFail();

        $policy->update([
            'work_days' => $validated['work_days'],
            'start_time' => today()->setTimeFromTimeString($validated['start_time']),
            'end_time' => today()->setTimeFromTimeString($validated['end_time']),
            'break_duration' => $validated['break_duration'] ?? 0,
            'late_tolerance' => $validated['late_tolerance'] ?? 0,
            'early_checkout_tolerance' => $validated['early_checkout_tolerance'] ?? 0,
            'overtime_after_minutes' => $validated['overtime_after_minutes'] ?? 0,
            'auto_checkout' => $validated['auto_checkout'] ?? false,
            'auto_checkout_time' => isset($validated['auto_checkout_time']) 
                ? today()->setTimeFromTimeString($validated['auto_checkout_time']) 
                : null,
        ]);

        // Ensure all employees still have this policy assigned
        \App\Models\Admin\Employee::where('instansi_id', $instansiId)
            ->whereNull('attendance_policy_id')
            ->update(['attendance_policy_id' => $policy->id]);

        return redirect()->route('admin.attendance-policy.index')
            ->with('success', 'Kebijakan absensi berhasil diperbarui.');
    }
}
