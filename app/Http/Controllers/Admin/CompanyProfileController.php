<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Check if user is admin but NOT an employee
        if ($user->employee) {
            abort(403, 'Access denied. Only administrators without an employee record can access this page.');
        }

        $instansi = $user->instansi;

        return view('admin.company-profile.index', compact('instansi'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Check if user is admin but NOT an employee
        if ($user->employee) {
            abort(403, 'Access denied. Only administrators without an employee record can access this page.');
        }

        $instansi = $user->instansi;

        $validated = $request->validate([
            'nama_instansi' => 'required|string|max:255',
            'display_name' => 'nullable|string|max:50',
            'npwp' => 'required|string|max:50',
            'nib' => 'nullable|string|max:50',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($instansi->logo) {
                Storage::disk('public')->delete($instansi->logo);
            }

            $path = $request->file('logo')->store('company-logos', 'public');
            $validated['logo'] = $path;
        }

        $instansi->update($validated);

        return redirect()->route('admin.company-profile.index')
            ->with('success', 'Company profile updated successfully.');
    }
}
