<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $query = Branch::where('company_id', Auth::user()->instansi_id)
                      ->with('instansi');

        // Check for branch restriction
        $user = Auth::user();
        if ($user->system_role_id !== 1 && $user->employee && $user->employee->branch_id) {
            $query->where('id', $user->employee->branch_id);
        }

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by search term
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('address', 'like', '%' . $request->search . '%');
            });
        }

        $branches = $query->orderBy('name')->paginate(15);

        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->system_role_id !== 1 && $user->employee && $user->employee->branch_id) {
            abort(403, 'You are not authorized to create new branches.');
        }
        return view('admin.branches.create');
    }

    public function store(Request $request)
    {
        // Check subscription limits
        $subscriptionService = new \App\Services\SubscriptionService(Auth::user()->instansi);
        if (!$subscriptionService->canAddBranch()) {
            return back()->with('error', 'Anda telah mencapai batas maksimal jumlah cabang untuk paket langganan Anda. Silakan upgrade paket untuk menambah cabang.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:1000',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'radius' => 'required|integer|min:10|max:1000',
            'timezone' => 'required|string|max:50',
            'is_active' => 'boolean',
        ]);

        Branch::create([
            'company_id' => Auth::user()->instansi_id,
            'name' => $request->name,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius,
            'timezone' => $request->timezone,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.branches.index')->with('success', 'Branch created successfully.');
    }

    public function show($id)
    {
        $branch = Branch::where('company_id', Auth::user()->instansi_id)
                       ->with(['instansi', 'employees', 'attendances'])
                       ->findOrFail($id);

        $user = Auth::user();
        if ($user->system_role_id !== 1 && $user->employee && $user->employee->branch_id && $user->employee->branch_id !== $branch->id) {
            abort(403);
        }

        return view('admin.branches.show', compact('branch'));
    }

    public function edit($id)
    {
        $branch = Branch::where('company_id', Auth::user()->instansi_id)
                       ->findOrFail($id);

        $user = Auth::user();
        if ($user->system_role_id !== 1 && $user->employee && $user->employee->branch_id && $user->employee->branch_id !== $branch->id) {
            abort(403);
        }

        return view('admin.branches.edit', compact('branch'));
    }

    public function update(Request $request, $id)
    {
        $branch = Branch::where('company_id', Auth::user()->instansi_id)
                       ->findOrFail($id);

        $user = Auth::user();
        if ($user->system_role_id !== 1 && $user->employee && $user->employee->branch_id && $user->employee->branch_id !== $branch->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:1000',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'radius' => 'required|integer|min:10|max:1000',
            'timezone' => 'required|string|max:50',
            'is_active' => 'boolean',
        ]);

        $branch->update([
            'name' => $request->name,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius,
            'timezone' => $request->timezone,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if ($user->system_role_id !== 1 && $user->employee && $user->employee->branch_id) {
             abort(403, 'You are not authorized to delete branches.');
        }

        $branch = Branch::where('company_id', Auth::user()->instansi_id)
                       ->findOrFail($id);

        // Check if branch has employees
        if ($branch->employees()->count() > 0) {
            return redirect()->route('admin.branches.index')->with('error', 'Cannot delete branch with active employees.');
        }

        $branch->delete();

        return redirect()->route('admin.branches.index')->with('success', 'Branch deleted successfully.');
    }
}
