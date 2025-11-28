<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Admin\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)
            ->with([
                'instansi', 
                'branch', 
                'division.policy', 
                'department', 
                'position', 
                'manager.user', 
                'manager.position',
                'policy',
                'subordinates.user',
                'subordinates.position'
            ])
            ->first();

        if (!$employee) {
            return redirect()->route('employee.dashboard')
                ->with('error', 'Employee profile not found');
        }

        return view('employee.profile.index', [
            'user' => $user,
            'employee' => $employee,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();

        if (!$employee) {
            return redirect()->route('employee.dashboard')
                ->with('error', 'Employee profile not found');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            
            // Personal Info
            'nik' => ['required', 'string', 'max:20', 'unique:employees,nik,' . $employee->id],
            'phone' => ['required', 'string', 'max:20'],
            'alamat_ktp' => ['required', 'string'],
            'address' => ['nullable', 'string'], // Domisili
            'date_of_birth' => ['required', 'date'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'gender' => ['required', 'in:male,female'],
            'status_perkawinan' => ['required', 'in:lajang,menikah,cerai'],
            'jumlah_tanggungan' => ['required', 'integer', 'min:0'],
            'npwp' => ['nullable', 'string', 'max:20'],

            // Emergency Contact
            'emergency_contact_name' => ['required', 'string', 'max:100'],
            'emergency_contact_relation' => ['required', 'string', 'max:50'],
            'emergency_contact_phone' => ['required', 'string', 'max:20'],

            // Bank Info
            'bank_name' => ['required', 'string', 'max:50'],
            'bank_account_number' => ['required', 'string', 'max:50'],
            'bank_account_holder' => ['required', 'string', 'max:100'],

            // Documents
            'foto_karyawan' => ['nullable', 'image', 'max:2048'],
            'scan_ktp' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'scan_npwp' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'scan_kk' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        // Update User
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        // Sync avatar with foto_karyawan if uploaded
        if ($request->hasFile('foto_karyawan')) {
             if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('foto_karyawan')->store('avatars', 'public'); // Store in avatars for user consistency
            $user->avatar = $path;
        }
        $user->save();

        // Handle File Uploads for Employee
        $employeeData = [
            'nik' => $validated['nik'],
            'phone' => $validated['phone'],
            'alamat_ktp' => $validated['alamat_ktp'],
            'address' => $validated['address'],
            'date_of_birth' => $validated['date_of_birth'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'gender' => $validated['gender'],
            'status_perkawinan' => $validated['status_perkawinan'],
            'jumlah_tanggungan' => $validated['jumlah_tanggungan'],
            'npwp' => $validated['npwp'],
            
            'emergency_contact_name' => $validated['emergency_contact_name'],
            'emergency_contact_relation' => $validated['emergency_contact_relation'],
            'emergency_contact_phone' => $validated['emergency_contact_phone'],
            
            'bank_name' => $validated['bank_name'],
            'bank_account_number' => $validated['bank_account_number'],
            'bank_account_holder' => $validated['bank_account_holder'],
        ];

        if ($request->hasFile('foto_karyawan')) {
            // Already stored in user avatar, but let's store reference or same path if we want separate
            // For now, let's say employee table also has foto_karyawan column.
            if ($employee->foto_karyawan) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($employee->foto_karyawan);
            }
            // We can reuse the path from user avatar or store again. Let's reuse.
            $employeeData['foto_karyawan'] = $user->avatar;
        }

        $documents = ['scan_ktp', 'scan_npwp', 'scan_kk'];
        foreach ($documents as $doc) {
            if ($request->hasFile($doc)) {
                if ($employee->$doc) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($employee->$doc);
                }
                $employeeData[$doc] = $request->file($doc)->store('employee_documents', 'public');
            }
        }

        $employee->update($employeeData);

        return redirect()->route('employee.profile')
            ->with('success', 'Profile updated successfully');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('employee.profile')
            ->with('success', 'Password updated successfully');
    }
}
