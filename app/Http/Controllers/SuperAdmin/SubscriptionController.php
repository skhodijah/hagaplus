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
        // Update expired subscriptions
        Subscription::where('end_date', '<', now())
            ->where('status', '!=', 'expired')
            ->update(['status' => 'expired']);

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

        // Settings table dropped, using defaults
        $paymentMethodsSetting = null;

        if ($paymentMethodsSetting) {
            $paymentMethods = json_decode($paymentMethodsSetting, true);
        } else {
            // Default payment methods
            $paymentMethods = [
                ['value' => 'transfer', 'label' => 'Transfer Bank', 'enabled' => true],
                ['value' => 'bank_transfer', 'label' => 'Bank Transfer', 'enabled' => true],
                ['value' => 'cash', 'label' => 'Tunai', 'enabled' => true],
                ['value' => 'credit_card', 'label' => 'Kartu Kredit', 'enabled' => true]
            ];
        }

        // Filter only enabled payment methods
        $enabledPaymentMethods = array_filter($paymentMethods, function ($method) {
            return $method['enabled'] ?? true;
        });

        $requestId = request('request_id');
        $subscriptionRequest = null;
        if ($requestId) {
            $subscriptionRequest = DB::table('subscription_requests')->find($requestId);
        }

        return view('superadmin.subscriptions.create', compact('instansis', 'packages', 'enabledPaymentMethods', 'subscriptionRequest'));
    }

    /**
     * Store a newly created subscription in storage.
     */
    public function store(Request $request)
    {
        // Settings table dropped, using defaults
        $paymentMethodsSetting = null;

        if ($paymentMethodsSetting) {
            $paymentMethods = json_decode($paymentMethodsSetting, true);
            $availablePaymentMethods = array_column(array_filter($paymentMethods, function ($method) {
                return $method['enabled'] ?? true;
            }), 'value');
        } else {
            // Default payment methods
            $availablePaymentMethods = ['transfer', 'bank_transfer', 'cash', 'credit_card'];
        }

        $validated = $request->validate([
            'instansi_id' => 'required|exists:instansis,id',
            'package_id' => 'required|exists:packages,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'price' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:' . implode(',', $availablePaymentMethods),
            'request_id' => 'nullable|exists:subscription_requests,id',
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

        $subscription = Subscription::create($validated);

        // Create or update payment history record
        try {
            if (isset($validated['request_id']) && $validated['request_id']) {
                // Update existing request
                DB::table('subscription_requests')
                    ->where('id', $validated['request_id'])
                    ->update([
                        'subscription_id' => $subscription->id,
                        'payment_status' => 'paid',
                        'updated_at' => now(),
                    ]);
            } else {
                // Create new request
                DB::table('subscription_requests')->insert([
                    'instansi_id' => $validated['instansi_id'],
                    'package_id' => $validated['package_id'],
                    'subscription_id' => $subscription->id,
                    'extension_months' => null,
                    'target_package_id' => null,
                    'amount' => $validated['price'],
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => 'paid', // Assuming payment is completed when subscription is created
                    'transaction_id' => 'SUB-' . $subscription->id . '-' . time(),
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Failed to create payment history record: ' . $e->getMessage(), [
                'subscription_id' => $subscription->id,
                'validated_data' => $validated
            ]);

            // Continue with the subscription creation even if payment history fails
            return redirect()->route('superadmin.subscriptions.index')
                ->with('success', 'Subscription berhasil dibuat, namun gagal mencatat history pembayaran.');
        }

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

        // Settings table dropped, using defaults
        $paymentMethodsSetting = null;

        if ($paymentMethodsSetting) {
            $paymentMethods = json_decode($paymentMethodsSetting, true);
        } else {
            // Default payment methods
            $paymentMethods = [
                ['value' => 'transfer', 'label' => 'Transfer Bank', 'enabled' => true],
                ['value' => 'bank_transfer', 'label' => 'Bank Transfer', 'enabled' => true],
                ['value' => 'cash', 'label' => 'Tunai', 'enabled' => true],
                ['value' => 'credit_card', 'label' => 'Kartu Kredit', 'enabled' => true]
            ];
        }

        // Filter only enabled payment methods
        $enabledPaymentMethods = array_filter($paymentMethods, function ($method) {
            return $method['enabled'] ?? true;
        });

        // Check if there's a pending payment for this subscription
        $pendingPayment = DB::table('subscription_requests')
            ->where('subscription_id', $id)
            ->where('payment_status', 'pending')
            ->first();

        // Auto-detect changes from pending payment notes
        $autoDetected = [];
        if ($pendingPayment && $pendingPayment->notes) {
            // Check for combined extension + upgrade first
            if (preg_match('/Perpanjangan (\d+) bulan \+ Upgrade.*ke paket ([^.\n]+)/i', $pendingPayment->notes, $matches)) {
                $extensionMonths = (int) $matches[1];
                $targetPackageName = trim($matches[2]);
                $autoDetected['end_date'] = $subscription->end_date->copy()->addMonths($extensionMonths)->format('Y-m-d');

                $targetPackage = $packages->firstWhere('name', $targetPackageName);
                if ($targetPackage) {
                    $autoDetected['package_id'] = $targetPackage->id;
                    $autoDetected['price'] = $targetPackage->price;
                }
            }
            // Check for extension only
            elseif (preg_match('/perpanjangan subscription (\d+) bulan/i', $pendingPayment->notes, $matches)) {
                $extensionMonths = (int) $matches[1];
                $autoDetected['end_date'] = $subscription->end_date->copy()->addMonths($extensionMonths)->format('Y-m-d');
            }
            // Check for upgrade only
            elseif (preg_match('/upgrade.*ke paket ([^.\n]+)/i', $pendingPayment->notes, $matches)) {
                $targetPackageName = trim($matches[1]);
                $targetPackage = $packages->firstWhere('name', $targetPackageName);
                if ($targetPackage) {
                    $autoDetected['package_id'] = $targetPackage->id;
                    $autoDetected['price'] = $targetPackage->price;
                }
            }
        }

        return view('superadmin.subscriptions.edit', compact('subscription', 'packages', 'enabledPaymentMethods', 'pendingPayment', 'autoDetected'));
    }

    /**
     * Update the specified subscription in storage.
     */
    public function update(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);

        // Settings table dropped, using defaults
        $paymentMethodsSetting = null;

        if ($paymentMethodsSetting) {
            $paymentMethods = json_decode($paymentMethodsSetting, true);
            $availablePaymentMethods = array_column(array_filter($paymentMethods, function ($method) {
                return $method['enabled'] ?? true;
            }), 'value');
        } else {
            // Default payment methods
            $availablePaymentMethods = ['transfer', 'bank_transfer', 'cash', 'credit_card'];
        }

        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:pending_verification,active,inactive,expired,cancelled',
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

        // Check if there's a pending payment for this subscription
        $pendingPayment = DB::table('subscription_requests')
            ->where('subscription_id', $subscription->id)
            ->where('payment_status', 'pending')
            ->first();

        if ($pendingPayment) {
            // Update payment status to paid
            DB::table('subscription_requests')
                ->where('id', $pendingPayment->id)
                ->update([
                    'payment_status' => 'paid',
                    'updated_at' => now()
                ]);
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
     * Remove the specified subscription from storage.
     */
    public function destroy($id)
    {
        $subscription = Subscription::findOrFail($id);

        // Delete related payment history records
        DB::table('subscription_requests')->where('subscription_id', $id)->delete();

        $subscription->delete();

        return redirect()->route('superadmin.subscriptions.index')
            ->with('success', 'Subscription berhasil dihapus.');
    }

    /**
     * Show subscription requests
     */
    public function subscriptionRequests(Request $request)
    {
        $query = DB::table('subscription_requests')
            ->leftJoin('instansis', 'subscription_requests.instansi_id', '=', 'instansis.id')
            ->leftJoin('packages', 'subscription_requests.package_id', '=', 'packages.id')
            ->leftJoin('users', 'subscription_requests.created_by', '=', 'users.id')
            ->leftJoin('payment_methods', 'subscription_requests.payment_method_id', '=', 'payment_methods.id')
            ->whereNotNull('subscription_requests.payment_proof') // Only show requests with payment proof uploaded
            ->select(
                'subscription_requests.*',
                'instansis.nama_instansi',
                'packages.name as package_name',
                'users.name as created_by_name',
                'payment_methods.name as payment_method_name'
            );

        // Apply filters
        if ($request->filled('status')) {
            $query->where('subscription_requests.payment_status', $request->status);
        }

        if ($request->filled('instansi_id')) {
            $query->where('subscription_requests.instansi_id', $request->instansi_id);
        }

        if ($request->filled('package_id')) {
            $query->where('subscription_requests.package_id', $request->package_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('subscription_requests.created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('subscription_requests.created_at', '<=', $request->date_to);
        }

        if ($request->filled('payment_method')) {
            $query->where('subscription_requests.payment_method', $request->payment_method);
        }

        // Search by transaction ID or instansi name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subscription_requests.transaction_id', 'like', "%{$search}%")
                    ->orWhere('instansis.nama_instansi', 'like', "%{$search}%");
            });
        }

        $payments = $query->orderBy('subscription_requests.created_at', 'desc')
            ->paginate(15)
            ->through(function ($payment) {
                // Cast created_at to Carbon instance
                $payment->created_at = \Carbon\Carbon::parse($payment->created_at);
                return $payment;
            });

        // Summary statistics
        $totalPayments = DB::table('subscription_requests')->count();
        $paidPayments = DB::table('subscription_requests')->where('payment_status', 'paid')->count();
        $pendingPayments = DB::table('subscription_requests')->where('payment_status', 'pending')->count();
        $totalRevenue = DB::table('subscription_requests')->where('payment_status', 'paid')->sum('amount');

        // Get filter options
        $instansis = \App\Models\SuperAdmin\Instansi::select('id', 'nama_instansi')->get();
        $packages = \App\Models\SuperAdmin\Package::select('id', 'name')->get();

        return view('superadmin.subscriptions.subscription-requests', compact(
            'payments',
            'totalPayments',
            'paidPayments',
            'pendingPayments',
            'totalRevenue',
            'instansis',
            'packages'
        ));
    }

    /**
     * Process pending payment (admin request)
     */
    public function processPayment($paymentId)
    {
        // Redirect to the transaction processing page
        return redirect()->route('superadmin.subscriptions.process-transaction', $paymentId);
    }
}
