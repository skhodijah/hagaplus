<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Attendance;
use App\Models\Core\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $instansiId = auth()->user()->instansi_id;
        $month = $request->get('month', now()->format('Y-m'));
        $startOfMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endOfMonth = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        $employees = User::where('role', 'employee')
            ->where('instansi_id', $instansiId)
            ->get();

        $attendances = Attendance::whereHas('user', function($query) use ($instansiId) {
            $query->where('instansi_id', $instansiId);
        })
        ->whereBetween('attendance_date', [$startOfMonth, $endOfMonth])
        ->get()
        ->groupBy(['user_id', 'attendance_date']);

        return view('admin.attendance.index', compact('employees', 'attendances', 'month'));
    }

    public function create()
    {
        $instansiId = auth()->user()->instansi_id;
        $employees = User::where('role', 'employee')
            ->where('instansi_id', $instansiId)
            ->get();
        return view('admin.attendance.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $instansiId = auth()->user()->instansi_id;

        $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($instansiId) {
                    $user = User::find($value);
                    if (!$user || $user->instansi_id !== $instansiId) {
                        $fail('The selected employee does not belong to your institution.');
                    }
                },
            ],
            'attendance_date' => 'required|date',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,late,partial,leave',
        ]);

        Attendance::create($request->all());

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance record created successfully.');
    }

    public function show($id)
    {
        $instansiId = auth()->user()->instansi_id;
        $attendance = Attendance::whereHas('user', function($query) use ($instansiId) {
            $query->where('instansi_id', $instansiId);
        })->findOrFail($id);

        return view('admin.attendance.show', compact('attendance'));
    }

    public function edit($id)
    {
        $instansiId = auth()->user()->instansi_id;
        $attendance = Attendance::whereHas('user', function($query) use ($instansiId) {
            $query->where('instansi_id', $instansiId);
        })->findOrFail($id);

        $employees = User::where('role', 'employee')
            ->where('instansi_id', $instansiId)
            ->get();

        return view('admin.attendance.edit', compact('attendance', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $instansiId = auth()->user()->instansi_id;
        $attendance = Attendance::whereHas('user', function($query) use ($instansiId) {
            $query->where('instansi_id', $instansiId);
        })->findOrFail($id);

        $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($instansiId) {
                    $user = User::find($value);
                    if (!$user || $user->instansi_id !== $instansiId) {
                        $fail('The selected employee does not belong to your institution.');
                    }
                },
            ],
            'attendance_date' => 'required|date',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,late,partial,leave',
        ]);

        $attendance->update($request->all());

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance record updated successfully.');
    }

    public function destroy($id)
    {
        $instansiId = auth()->user()->instansi_id;
        $attendance = Attendance::whereHas('user', function($query) use ($instansiId) {
            $query->where('instansi_id', $instansiId);
        })->findOrFail($id);

        $attendance->delete();

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance record deleted successfully.');
    }

    public function employeeAttendance($userId, Request $request)
    {
        $instansiId = auth()->user()->instansi_id;
        $month = $request->get('month', now()->format('Y-m'));
        $startOfMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endOfMonth = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        $user = User::where('instansi_id', $instansiId)->findOrFail($userId);
        $attendances = Attendance::where('user_id', $userId)
            ->whereBetween('attendance_date', [$startOfMonth, $endOfMonth])
            ->orderBy('attendance_date')
            ->get();

        return view('admin.attendance.employee', compact('user', 'attendances', 'month'));
    }

    public function toggle(Request $request)
    {
        $instansiId = auth()->user()->instansi_id;

        $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($instansiId) {
                    $user = User::find($value);
                    if (!$user || $user->instansi_id !== $instansiId) {
                        $fail('The selected employee does not belong to your institution.');
                    }
                },
            ],
            'date' => 'required|date',
        ]);

        $attendance = Attendance::where('user_id', $request->user_id)
            ->where('attendance_date', $request->date)
            ->first();

        if ($attendance) {
            $attendance->delete();
            return response()->json(['status' => 'absent']);
        } else {
            Attendance::create([
                'user_id' => $request->user_id,
                'attendance_date' => $request->date,
                'status' => 'present',
            ]);
            return response()->json(['status' => 'present']);
        }
    }
}
