<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    /**
     * Show subscription analytics
     */
    public function analytics()
    {
        // Get subscription metrics
        $totalSubscriptions = Subscription::count();
        $activeSubscriptions = Subscription::where('status', 'active')->count();
        $expiredSubscriptions = Subscription::where('status', 'expired')->count();
        $trialSubscriptions = Subscription::where('is_trial', true)->count();

        // Revenue metrics
        $totalRevenue = Subscription::where('status', 'active')->sum('price');
        $monthlyRevenue = Subscription::where('status', 'active')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('price');

        // Package distribution
        $packageDistribution = Subscription::with('package')
            ->selectRaw('package_id, COUNT(*) as count')
            ->groupBy('package_id')
            ->get()
            ->map(function ($item) use ($totalSubscriptions) {
                return [
                    'package' => $item->package->name ?? 'Unknown',
                    'count' => $item->count,
                    'percentage' => $totalSubscriptions > 0 ? round(($item->count / $totalSubscriptions) * 100, 1) : 0
                ];
            });

        // Monthly growth (last 12 months)
        $monthlyGrowth = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Subscription::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $monthlyGrowth[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }

        // Status distribution
        $statusDistribution = Subscription::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->map(function ($item) use ($totalSubscriptions) {
                return [
                    'status' => ucfirst($item->status),
                    'count' => $item->count,
                    'percentage' => $totalSubscriptions > 0 ? round(($item->count / $totalSubscriptions) * 100, 1) : 0
                ];
            });

        // Top packages by revenue
        $topPackages = Subscription::with('package')
            ->selectRaw('package_id, SUM(price) as revenue, COUNT(*) as subscriptions')
            ->where('status', 'active')
            ->groupBy('package_id')
            ->orderBy('revenue', 'desc')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'package' => $item->package->name ?? 'Unknown',
                    'revenue' => $item->revenue,
                    'subscriptions' => $item->subscriptions
                ];
            });

        return view('superadmin.subscriptions.analytics', compact(
            'totalSubscriptions',
            'activeSubscriptions',
            'expiredSubscriptions',
            'trialSubscriptions',
            'totalRevenue',
            'monthlyRevenue',
            'packageDistribution',
            'monthlyGrowth',
            'statusDistribution',
            'topPackages'
        ));
    }

    /**
     * Show payment history
     */
    public function paymentHistory(Request $request)
    {
        $query = DB::table('subscription_history')
            ->leftJoin('instansis', 'subscription_history.company_id', '=', 'instansis.id')
            ->leftJoin('packages', 'subscription_history.package_id', '=', 'packages.id')
            ->leftJoin('users', 'subscription_history.created_by', '=', 'users.id')
            ->select(
                'subscription_history.*',
                'instansis.nama_instansi',
                'packages.name as package_name',
                'users.name as created_by_name'
            );

        // Apply filters
        if ($request->filled('status')) {
            $query->where('subscription_history.payment_status', $request->status);
        }

        if ($request->filled('instansi_id')) {
            $query->where('subscription_history.company_id', $request->instansi_id);
        }

        if ($request->filled('package_id')) {
            $query->where('subscription_history.package_id', $request->package_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('subscription_history.created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('subscription_history.created_at', '<=', $request->date_to);
        }

        if ($request->filled('payment_method')) {
            $query->where('subscription_history.payment_method', $request->payment_method);
        }

        // Search by transaction ID or instansi name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subscription_history.transaction_id', 'like', "%{$search}%")
                    ->orWhere('instansis.nama_instansi', 'like', "%{$search}%");
            });
        }

        $payments = $query->orderBy('subscription_history.created_at', 'desc')
            ->paginate(15)
            ->through(function ($payment) {
                // Cast created_at to Carbon instance
                $payment->created_at = \Carbon\Carbon::parse($payment->created_at);
                return $payment;
            });

        // Summary statistics
        $totalPayments = DB::table('subscription_history')->count();
        $paidPayments = DB::table('subscription_history')->where('payment_status', 'paid')->count();
        $pendingPayments = DB::table('subscription_history')->where('payment_status', 'pending')->count();
        $totalRevenue = DB::table('subscription_history')->where('payment_status', 'paid')->sum('amount');

        // Get filter options
        $instansis = \App\Models\SuperAdmin\Instansi::select('id', 'nama_instansi')->get();
        $packages = \App\Models\SuperAdmin\Package::select('id', 'name')->get();

        return view('superadmin.subscriptions.payment-history', compact(
            'payments',
            'totalPayments',
            'paidPayments',
            'pendingPayments',
            'totalRevenue',
            'instansis',
            'packages'
        ));
    }
}
