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

        // Get current user and check for branch restriction
        $user = auth()->user();
        $branchId = null;
        if ($user->system_role_id !== 1 && $user->employee && $user->employee->branch_id) {
            $branchId = $user->employee->branch_id;
        }

        // Get all employees with their branch information
        $employeesQuery = User::where('instansi_id', $instansiId)
            ->has('employee')
            ->with(['employee.branch', 'employee.position', 'systemRole']);

        if ($branchId) {
            $employeesQuery->whereHas('employee', function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        $employees = $employeesQuery->get();

        $attendances = Attendance::whereHas('user', function ($query) use ($instansiId, $branchId) {
            $query->where('instansi_id', $instansiId);
            if ($branchId) {
                $query->whereHas('employee', function($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                });
            }
        })
            ->whereBetween('attendance_date', [$startOfMonth, $endOfMonth])
            ->get()
            ->groupBy(['user_id', 'attendance_date'])
            ->map(function ($userAttendances) {
                return $userAttendances->map(function ($dateAttendances) {
                    // Since we have unique constraint on user_id + attendance_date,
                    // there should only be one attendance per user per date
                    return $dateAttendances->first();
                });
            });

        // Group employees by branch
        $employeesByBranch = $employees->groupBy(function ($employee) {
            return $employee->employee && $employee->employee->branch
                ? $employee->employee->branch->id
                : 'no-branch';
        });

        // Get all branches for this instansi
        $branchesQuery = \App\Models\Admin\Branch::where('company_id', $instansiId)
            ->where('is_active', true)
            ->orderBy('name');

        if ($branchId) {
            $branchesQuery->where('id', $branchId);
        }

        $branches = $branchesQuery->get();

        // Get attendances with photos for the selfie gallery
        $attendancesWithPhotos = Attendance::whereHas('user', function ($query) use ($instansiId, $branchId) {
            $query->where('instansi_id', $instansiId);
            if ($branchId) {
                $query->whereHas('employee', function($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                });
            }
        })
            ->whereBetween('attendance_date', [$startOfMonth, $endOfMonth])
            ->where(function ($query) {
                $query->whereNotNull('check_in_photo')
                    ->orWhereNotNull('check_out_photo');
            })
            ->with('user')
            ->orderBy('attendance_date', 'desc')
            ->orderBy('check_in_time', 'desc')
            ->get();

        // Get approved leaves for the month
        $leaves = \App\Models\Admin\Leave::whereHas('user', function ($query) use ($instansiId, $branchId) {
            $query->where('instansi_id', $instansiId);
            if ($branchId) {
                $query->whereHas('employee', function($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                });
            }
        })
            ->where('status', 'approved')
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                    ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth])
                    ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                        $q->where('start_date', '<', $startOfMonth)
                            ->where('end_date', '>', $endOfMonth);
                    });
            })
            ->get();

        // Merge leaves into attendances
        foreach ($leaves as $leave) {
            $startDate = $leave->start_date < $startOfMonth ? $startOfMonth : $leave->start_date;
            $endDate = $leave->end_date > $endOfMonth ? $endOfMonth : $leave->end_date;

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $dateString = $date->format('Y-m-d 00:00:00');
                
                // Check if attendance already exists for this user and date
                $existingAttendance = $attendances->get($leave->user_id, collect())->get($dateString);

                if (!$existingAttendance) {
                    // Create a virtual attendance object for the leave
                    $virtualAttendance = new Attendance([
                        'user_id' => $leave->user_id,
                        'attendance_date' => $date->copy(),
                        'status' => 'leave',
                    ]);
                    // Manually set the ID to allow linking to leave details if needed (or just use null/dummy)
                    // For now, we just need it to display 'leave' status. 
                    // We can attach the leave object to it if we want to show details.
                    $virtualAttendance->leave_details = $leave;

                    // Add to the collection
                    if (!$attendances->has($leave->user_id)) {
                        $attendances->put($leave->user_id, collect());
                    }
                    $attendances->get($leave->user_id)->put($dateString, $virtualAttendance);
                }
            }
        }

        return view('admin.attendance.index', compact('employees', 'attendances', 'month', 'attendancesWithPhotos', 'employeesByBranch', 'branches'));
    }

    public function create()
    {
        $instansiId = auth()->user()->instansi_id;
        
        // Check for branch restriction
        $user = auth()->user();
        $branchId = null;
        if ($user->system_role_id !== 1 && $user->employee && $user->employee->branch_id) {
            $branchId = $user->employee->branch_id;
        }

        $employeesQuery = User::whereHas('systemRole', function($q) {
                $q->where('slug', 'employee');
            })
            ->where('instansi_id', $instansiId);
            
        if ($branchId) {
            $employeesQuery->whereHas('employee', function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }
        
        $employees = $employeesQuery->get();
        return view('admin.attendance.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $instansiId = auth()->user()->instansi_id;
        
        // Check for branch restriction
        $user = auth()->user();
        $branchId = null;
        if ($user->system_role_id !== 1 && $user->employee && $user->employee->branch_id) {
            $branchId = $user->employee->branch_id;
        }

        $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($instansiId, $branchId) {
                    $user = User::find($value);
                    if (!$user || $user->instansi_id !== $instansiId) {
                        $fail('The selected employee does not belong to your institution.');
                    }
                    if ($branchId && $user->employee && $user->employee->branch_id !== $branchId) {
                         $fail('The selected employee does not belong to your branch.');
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
        $attendance = Attendance::whereHas('user', function ($query) use ($instansiId) {
            $query->where('instansi_id', $instansiId);
        })->findOrFail($id);

        return view('admin.attendance.show', compact('attendance'));
    }

    public function edit($id)
    {
        $instansiId = auth()->user()->instansi_id;
        $attendance = Attendance::whereHas('user', function ($query) use ($instansiId) {
            $query->where('instansi_id', $instansiId);
        })->findOrFail($id);

        // Check for branch restriction
        $user = auth()->user();
        $branchId = null;
        if ($user->system_role_id !== 1 && $user->employee && $user->employee->branch_id) {
            $branchId = $user->employee->branch_id;
        }

        $employeesQuery = User::whereHas('systemRole', function($q) {
                $q->where('slug', 'employee');
            })
            ->where('instansi_id', $instansiId);

        if ($branchId) {
            $employeesQuery->whereHas('employee', function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }
        
        $employees = $employeesQuery->get();

        return view('admin.attendance.edit', compact('attendance', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $instansiId = auth()->user()->instansi_id;
        $attendance = Attendance::whereHas('user', function ($query) use ($instansiId) {
            $query->where('instansi_id', $instansiId);
        })->findOrFail($id);

        // Check for branch restriction
        $user = auth()->user();
        $branchId = null;
        if ($user->system_role_id !== 1 && $user->employee && $user->employee->branch_id) {
            $branchId = $user->employee->branch_id;
        }

        $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($instansiId, $branchId) {
                    $user = User::find($value);
                    if (!$user || $user->instansi_id !== $instansiId) {
                        $fail('The selected employee does not belong to your institution.');
                    }
                    if ($branchId && $user->employee && $user->employee->branch_id !== $branchId) {
                         $fail('The selected employee does not belong to your branch.');
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
        $attendance = Attendance::whereHas('user', function ($query) use ($instansiId) {
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

        // Get approved leaves for the employee in this month
        $leaves = \App\Models\Admin\Leave::where('user_id', $userId)
            ->where('status', 'approved')
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                    ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth])
                    ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                        $q->where('start_date', '<', $startOfMonth)
                            ->where('end_date', '>', $endOfMonth);
                    });
            })
            ->get();

        // Merge leaves into attendances
        foreach ($leaves as $leave) {
            $startDate = $leave->start_date < $startOfMonth ? $startOfMonth : $leave->start_date;
            $endDate = $leave->end_date > $endOfMonth ? $endOfMonth : $leave->end_date;

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                // Check if attendance already exists
                $exists = $attendances->contains(function ($att) use ($date) {
                    return $att->attendance_date->isSameDay($date);
                });

                if (!$exists) {
                    $virtualAttendance = new Attendance([
                        'user_id' => $userId,
                        'attendance_date' => $date->copy(),
                        'status' => 'leave',
                    ]);
                    $virtualAttendance->leave_details = $leave;
                    $attendances->push($virtualAttendance);
                }
            }
        }

        $attendances = $attendances->sortBy('attendance_date');

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
