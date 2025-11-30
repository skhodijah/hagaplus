<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin\Instansi;
use App\Models\SuperAdmin\Package;
use App\Models\SuperAdmin\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class InstansiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all non-deleted instansi with their latest subscription and package info
        $instansis = Instansi::with([
            'package',
            'subscriptions' => function ($query) {
                $query->orderByDesc('end_date')->limit(1);
            },
        ])->latest()->paginate(10);
        return view('superadmin.instansi.index', compact('instansis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $packages = Package::where('is_active', true)->get();
        return view('superadmin.instansi.create', compact('packages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_instansi' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:instansis,email',
            'phone' => 'nullable|string|max:20',
        ]);

        // Create new instansi without subscription details
        $instansi = Instansi::create([
            'nama_instansi' => $validated['nama_instansi'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('superadmin.instansi.index')
            ->with('success', 'Instansi berhasil ditambahkan. Silakan buat subscription di menu Subscriptions.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Instansi $instansi)
    {
        $instansi->load(['subscriptions.package']);
        return view('superadmin.instansi.show', compact('instansi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Instansi $instansi)
    {
        $packages = Package::where('is_active', true)->get();
        $instansi->load(['subscriptions']);
        return view('superadmin.instansi.edit', compact('instansi', 'packages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Instansi $instansi)
    {
        $validated = $request->validate([
            'nama_instansi' => 'required|string|max:255',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('instansis', 'email')->ignore($instansi->id),
            ],
            'phone' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        // Map form fields to database columns
        $updateData = [
            'nama_instansi' => $validated['nama_instansi'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['alamat'] ?? null,
            'is_active' => $validated['is_active'],
        ];

        // Update instansi
        $instansi->update($updateData);

        return redirect()->route('superadmin.instansi.index')
            ->with('success', 'Instansi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Instansi $instansi)
    {
        // Soft delete the instansi
        $instansi->delete();

        return redirect()->route('superadmin.instansi.index')
            ->with('success', 'Instansi berhasil dihapus (masuk ke arsip).');
    }

    /**
     * Display a listing of the trashed resources.
     */
    public function trash()
    {
        $instansis = Instansi::onlyTrashed()->latest()->paginate(10);
        return view('superadmin.instansi.trash', compact('instansis'));
    }

    /**
     * Restore the specified resource from trash.
     */
    public function restore($id)
    {
        $instansi = Instansi::onlyTrashed()->findOrFail($id);
        $instansi->restore();

        return redirect()->route('superadmin.instansi.trash')
            ->with('success', 'Instansi berhasil dipulihkan.');
    }

    /**
     * Permanently delete the specified resource.
     */
    public function forceDelete($id)
    {
        $instansi = Instansi::onlyTrashed()->findOrFail($id);

        // Delete logo if exists
        if ($instansi->logo) {
            $logo = str_replace('/storage', 'public', $instansi->logo);
            Storage::delete($logo);
        }

        // Permanently delete
        $instansi->forceDelete();

        return redirect()->route('superadmin.instansi.trash')
            ->with('success', 'Instansi berhasil dihapus permanen.');
    }


    public function monitoring()
    {
        $instansis = Instansi::with([
            'package',
            'subscriptions' => function ($q) {
                $q->orderByDesc('end_date')->limit(1);
            }
        ])->get();

        $data = $instansis->map(function ($instansi) {
            $latest = $instansi->subscriptions->first();
            $endDate = $latest?->end_date; // Carbon|DateTime|null
            $daysRemaining = $endDate ? now()->diffInDays($endDate, false) : null;
            $employeesCount = \Illuminate\Support\Facades\DB::table('employees')
                ->where('instansi_id', $instansi->id)
                ->count();

            return (object) [
                'id' => $instansi->id,
                'nama_instansi' => $instansi->nama_instansi,

                'is_active' => (bool) $instansi->is_active,
                'package_name' => $instansi->package->name ?? '-',
                'subscription_status' => $latest?->current_status ?? null,
                'subscription_end' => $endDate ? $endDate->format('Y-m-d') : null,
                'days_remaining' => $daysRemaining,
                'employees_count' => $employeesCount,
                'max_employees' => $instansi->max_employees,
            ];
        });

        return view('superadmin.instansi.monitoring', [
            'items' => $data,
            'counts' => [
                'total' => $data->count(),
                'active' => $data->where('is_active', true)->count(),
                'expired' => $data->where('subscription_status', 'expired')->count(),
                'expiring_7' => $data->filter(function ($i) { return isset($i->days_remaining) && $i->days_remaining <= 7 && $i->days_remaining >= 0; })->count(),
            ],
        ]);
    }

    /**
     * Toggle the status of the specified instansi.
     */
    public function toggleStatus(Instansi $instansi)
    {
        $instansi->update([
            'is_active' => !$instansi->is_active
        ]);

        $statusText = $instansi->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return response()->json([
            'success' => true,
            'message' => 'Status instansi berhasil ' . $statusText,
            'is_active' => $instansi->is_active
        ]);
    }
}
