<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuperAdmin\Subscription;

class SubscriptionController extends Controller
{
    /**
     * Tampilkan daftar semua subscription
     */
    public function index()
    {
        // Ambil data subscription dengan relasi instansi & package
        $subscriptions = Subscription::with(['instansi', 'package'])->paginate(10);

        return view('superadmin.subscriptions.index', compact('subscriptions'));
    }

    /**
     * Tampilkan detail subscription tertentu
     */
    public function show($id)
    {
        $subscription = Subscription::with(['instansi', 'package'])->findOrFail($id);

        return view('superadmin.subscriptions.show', compact('subscription'));
    }

    /**
     * Show the form for creating a new subscription.
     */
    public function create()
    {
        $instansis = \App\Models\SuperAdmin\Instansi::where('is_active', true)->get();
        $packages = \App\Models\SuperAdmin\Package::where('is_active', true)->get();

        return view('superadmin.subscriptions.create', compact('instansis', 'packages'));
    }

    /**
     * Store a newly created subscription in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'instansi_id' => 'required|exists:instansis,id',
            'package_id' => 'required|exists:packages,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'price' => 'nullable|numeric|min:0',
        ]);

        // Prevent overlapping active-like subscriptions for the same instansi
        $overlap = Subscription::where('instansi_id', $validated['instansi_id'])
            ->whereIn('status', ['active', 'inactive'])
            ->where(function ($q) use ($validated) {
                $q->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                  ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                  ->orWhere(function ($qq) use ($validated) {
                      $qq->where('start_date', '<=', $validated['start_date'])
                         ->where('end_date', '>=', $validated['end_date']);
                  });
            })
            ->exists();

        if ($overlap) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['start_date' => 'Rentang tanggal bertumpuk dengan subscription lain untuk instansi ini.']);
        }

        // Default status active when creating
        $validated['status'] = 'active';

        // If price not provided, use package price
        if (!isset($validated['price'])) {
            $validated['price'] = optional(\App\Models\SuperAdmin\Package::find($validated['package_id']))->price ?? 0;
        }

        Subscription::create($validated);

        return redirect()->route('superadmin.subscriptions.index')
            ->with('success', 'Subscription berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified subscription.
     */
    public function edit($id)
    {
        $subscription = Subscription::with(['instansi', 'package'])->findOrFail($id);
        $packages = \App\Models\SuperAdmin\Package::where('is_active', true)->get();

        return view('superadmin.subscriptions.edit', compact('subscription', 'packages'));
    }

    /**
     * Update the specified subscription in storage.
     */
    public function update(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);

        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,inactive,suspended,expired,canceled',
            'price' => 'nullable|numeric|min:0',
        ]);

        // Prevent overlapping periods with other subscriptions for same instansi
        $overlap = Subscription::where('instansi_id', $subscription->instansi_id)
            ->where('id', '!=', $subscription->id)
            ->whereIn('status', ['active', 'inactive'])
            ->where(function ($q) use ($validated) {
                $q->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                  ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                  ->orWhere(function ($qq) use ($validated) {
                      $qq->where('start_date', '<=', $validated['start_date'])
                         ->where('end_date', '>=', $validated['end_date']);
                  });
            })
            ->exists();

        if ($overlap) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['start_date' => 'Rentang tanggal bertumpuk dengan subscription lain untuk instansi ini.']);
        }

        // If price not provided, use package price
        if (!isset($validated['price'])) {
            $validated['price'] = optional(\App\Models\SuperAdmin\Package::find($validated['package_id']))->price ?? $subscription->price;
        }

        $subscription->update($validated);

        return redirect()->route('superadmin.subscriptions.index')
                         ->with('success', 'Subscription berhasil diperbarui.');
    }

    /**
     * Extend subscription by 1 month.
     */
    public function extend($id)
    {
        $subscription = Subscription::findOrFail($id);

        $newEndDate = $subscription->end_date->addMonth();

        $subscription->update([
            'end_date' => $newEndDate,
            'status' => 'active',
        ]);

        return redirect()->back()
                         ->with('success', 'Subscription berhasil diperpanjang hingga ' . $newEndDate->format('d M Y'));
    }
}
