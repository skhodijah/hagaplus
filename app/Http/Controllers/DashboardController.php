<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Package;
use App\Models\Payroll;
use App\Models\Attendance;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalCompanies' => Company::count(),
            'totalBranches' => Branch::count(),
            'totalUsers' => User::count(),
            'totalPackages' => Package::count(),
            'totalPayrolls' => Payroll::count(),
            'totalAttendances' => Attendance::count(),
            'totalLeaves' => Leave::count(),
            'recentUsers' => User::latest()->take(5)->get(),
            'recentCompanies' => Company::latest()->take(5)->get(),
        ];

        return view('dashboard', compact('data'));
    }

    public function companies(): View
    {
        $companies = Company::with('package')->paginate(10);
        return view('admin.companies', compact('companies'));
    }

    public function createCompany(): View
    {
        $packages = Package::all();
        return view('admin.companies-create', compact('packages'));
    }

    public function storeCompany(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'package_id' => 'nullable|exists:packages,id',
            'subscription_start' => 'nullable|date',
            'subscription_end' => 'nullable|date',
            'max_employees' => 'integer|min:1',
            'max_branches' => 'integer|min:1',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Company::create($validated);

        return redirect()->route('admin.companies')->with('success', 'Company created successfully.');
    }

    public function editCompany(Company $company): View
    {
        $packages = Package::all();
        return view('admin.companies-edit', compact('company', 'packages'));
    }

    public function updateCompany(Request $request, Company $company): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email,' . $company->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'package_id' => 'nullable|exists:packages,id',
            'subscription_start' => 'nullable|date',
            'subscription_end' => 'nullable|date',
            'max_employees' => 'integer|min:1',
            'max_branches' => 'integer|min:1',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $company->update($validated);

        return redirect()->route('admin.companies')->with('success', 'Company updated successfully.');
    }

    public function deleteCompany(Company $company): RedirectResponse
    {
        $company->delete();

        return redirect()->route('admin.companies')->with('success', 'Company deleted successfully.');
    }
}