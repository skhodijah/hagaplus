<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    /**
     * Check if user is allowed to access subscription management
     */
    private function checkAccess()
    {
        $user = auth()->user();
        // Check if user is in employees table
        $isEmployee = DB::table('employees')->where('user_id', $user->id)->exists();
        
        if ($isEmployee) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk admin yang bukan pegawai.');
        }
    }

    /**
     * Display the subscription management page for admin
     */
    public function index()
    {
        $this->checkAccess();
        $user = auth()->user();
        $instansi = $user->instansi;

        if (!$instansi) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda tidak terhubung dengan Instansi manapun. Silakan hubungi Super Admin.');
        }

        // Update expired subscriptions
        DB::table('subscriptions')
            ->where('instansi_id', $instansi->id)
            ->where('end_date', '<', now())
            ->where('status', '!=', 'expired')
            ->update(['status' => 'expired']);

        // Get current subscription
        // Get current subscription
        $currentSubscription = \App\Models\SuperAdmin\Subscription::with('package')
            ->where('instansi_id', $instansi->id)
            ->where('status', 'active')
            ->first();

        // Get subscription history
        $subscriptionHistory = DB::table('subscriptions')
            ->leftJoin('packages', 'subscriptions.package_id', '=', 'packages.id')
            ->where('subscriptions.instansi_id', $instansi->id)
            ->select('subscriptions.*', 'packages.name as package_name')
            ->orderBy('subscriptions.created_at', 'desc')
            ->get();

        // Get payment history
        $paymentHistory = DB::table('subscription_requests')
            ->leftJoin('packages', 'subscription_requests.package_id', '=', 'packages.id')
            ->leftJoin('payment_methods', 'subscription_requests.payment_method_id', '=', 'payment_methods.id')
            ->where('subscription_requests.instansi_id', $instansi->id)
            ->select(
                'subscription_requests.*', 
                'packages.name as package_name',
                'payment_methods.name as payment_method_name'
            )
            ->orderBy('subscription_requests.created_at', 'desc')
            ->get();

        // Get available packages (exclude trial/free packages)
        $packages = DB::table('packages')
            ->where('is_active', true)
            ->where('price', '>', 0)
            ->where(function($query) {
                $query->where('name', 'NOT LIKE', '%trial%')
                      ->where('name', 'NOT LIKE', '%free%');
            })
            ->orderBy('price', 'asc')
            ->get();

        // Check for pending request
        $pendingRequest = DB::table('subscription_requests')
            ->where('instansi_id', $instansi->id)
            ->whereIn('payment_status', ['pending', 'pending_verification'])
            ->first();

        return view('admin.subscription.index', compact(
            'currentSubscription',
            'subscriptionHistory',
            'paymentHistory',
            'packages',
            'instansi',
            'pendingRequest'
        ));
    }

    /**
     * Unified request method for extension, upgrade, or both
     */
    public function handleRequest(Request $request)
    {
        $this->checkAccess();
        $request->validate([
            'request_type' => 'required|in:extension,upgrade,both',
            'extension_months' => 'required_if:request_type,extension|required_if:request_type,both|integer|min:1|max:12',
            'target_package_id' => 'required_if:request_type,upgrade|required_if:request_type,both|exists:packages,id',
            'notes' => 'nullable|string|max:500'
        ]);

        $user = auth()->user();
        $instansi = $user->instansi;

        if (!$instansi) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda tidak terhubung dengan Instansi manapun.');
        }

        // Check if there are already pending requests
        $existingPendingRequest = DB::table('subscription_requests')
            ->where('instansi_id', $instansi->id)
            ->whereIn('payment_status', ['pending', 'pending_verification'])
            ->exists();

        if ($existingPendingRequest) {
            $pendingRequest = DB::table('subscription_requests')
                ->where('instansi_id', $instansi->id)
                ->whereIn('payment_status', ['pending', 'pending_verification'])
                ->first();
                
            return redirect()->route('admin.subscription.transaction', $pendingRequest->id)
                ->with('info', 'Anda memiliki permintaan subscription yang sedang diproses. Silakan selesaikan pembayaran ini terlebih dahulu.');
        }

        // Get current subscription (active or inactive)
        $currentSubscription = DB::table('subscriptions')
            ->where('instansi_id', $instansi->id)
            ->whereIn('status', ['active', 'inactive', 'expired'])
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$currentSubscription) {
            // Handle New Subscription
            $targetPackage = DB::table('packages')->find($request->target_package_id);
            
            if (!$targetPackage) {
                 return redirect()->back()->with('error', 'Paket tidak ditemukan.');
            }

            $paymentId = DB::table('subscription_requests')->insertGetId([
                'instansi_id' => $instansi->id,
                'package_id' => $targetPackage->id,
                'subscription_id' => null,
                'extension_months' => null,
                'target_package_id' => $targetPackage->id,
                'amount' => $targetPackage->price,
                'payment_method' => 'pending',
                'payment_status' => 'pending',
                'transaction_id' => 'NEW-' . $instansi->id . '-' . time(),
                'notes' => "Pembelian paket baru {$targetPackage->name}. " . ($request->notes ?? ''),
                'created_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            return redirect()->route('admin.subscription.transaction', $paymentId)
                ->with('success', 'Permintaan subscription baru berhasil dibuat. Silakan lakukan pembayaran.');

            return redirect()->route('admin.subscription.transaction', $paymentId)
                ->with('success', 'Permintaan subscription baru berhasil dibuat. Silakan lakukan pembayaran.');
        }

        $requestType = $request->request_type;
        $totalAmount = 0;
        $notes = [];
        $transactionPrefix = '';
        $newEndDate = null;
        $whatsappDetails = [];
        
        // Initialize payment data with default values
        $paymentData = [
            'instansi_id' => $instansi->id,
            'subscription_id' => $currentSubscription->id,
            'package_id' => $currentSubscription->package_id,
            'amount' => 0,
            'payment_method' => 'pending',
            'payment_status' => 'pending',
            'transaction_id' => '',
            'notes' => '',
            'created_by' => $user->id,
            'start_date' => $currentSubscription->start_date,
            'end_date' => $currentSubscription->end_date,
            'created_at' => now(),
            'updated_at' => now()
        ];

        // Handle extension (if requested)
        if (in_array($requestType, ['extension', 'both'])) {
            $extensionMonths = (int) $request->extension_months;
            $currentDate = now();
            $currentEndDate = Carbon::parse($currentSubscription->end_date);
            
            // Always use the current date for extension to prevent gaps in subscription
            $newEndDate = $currentDate->copy()->addMonths($extensionMonths);
            $notes[] = "Perpanjangan subscription {$extensionMonths} bulan (dari tanggal permintaan)";
            
            // Add a note if the subscription has already expired
            if ($currentDate->gt($currentEndDate)) {
                $notes[] = "Masa aktif sebelumnya telah berakhir pada {$currentEndDate->format('d M Y')}";
            }
            
            $extensionCost = $currentSubscription->price * $extensionMonths;
            $totalAmount += $extensionCost;
            
            $paymentData['extension_months'] = $extensionMonths;
            $paymentData['new_end_date'] = $newEndDate->format('Y-m-d');
            $whatsappDetails[] = "perpanjangan subscription {$extensionMonths} bulan";
            $transactionPrefix = $requestType === 'extension' ? 'EXT' : 'BOTH';
        }

        // Handle upgrade (if requested)
        if (in_array($requestType, ['upgrade', 'both'])) {
            $targetPackage = DB::table('packages')->find($request->target_package_id);
            
            if ($targetPackage) {
                // Calculate upgrade cost (difference in monthly price)
                $upgradeCost = max(0, $targetPackage->price - $currentSubscription->price);
                $totalAmount += $upgradeCost;
                
                $paymentData['target_package_id'] = $targetPackage->id;
                
                $notes[] = "Upgrade ke paket {$targetPackage->name}";
                $whatsappDetails[] = "upgrade ke paket {$targetPackage->name}";
                $transactionPrefix = $requestType === 'upgrade' ? 'UPG' : 'BOTH';
            }
        }

        // Add any additional notes
        if ($request->notes) {
            $notes[] = $request->notes;
        }

        // Set final payment data
        $paymentData['amount'] = $totalAmount;
        $paymentData['notes'] = implode('. ', array_filter($notes)) . '.';
        $paymentData['transaction_id'] = $transactionPrefix . '-' . $currentSubscription->id . '-' . time();

        // Add required fields to payment data
        $paymentData['created_by'] = $user->id;
        $paymentData['created_at'] = now();
        $paymentData['updated_at'] = now();
        
        // Remove any fields that don't exist in the table
        unset($paymentData['start_date'], $paymentData['end_date'], $paymentData['new_end_date']);
        
        // Create the subscription request
        $paymentId = DB::table('subscription_requests')->insertGetId($paymentData);

        // Redirect to payment page
        return redirect()->route('admin.subscription.transaction', $paymentId)
            ->with('success', 'Permintaan subscription berhasil dibuat. Silakan lakukan pembayaran.');
    }

    /**
     * Request subscription extension
     */
    public function requestExtension(Request $request)
    {
        $this->checkAccess();
        $request->validate([
            'extension_months' => 'required|integer|min:1|max:12',
            'notes' => 'nullable|string|max:500'
        ]);

        $user = auth()->user();
        
        if (!$user->instansi) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda tidak terhubung dengan Instansi manapun.');
        }

        $instansi = $user->instansi->load('package');

        // Check if there are already pending requests
        $existingPendingRequest = DB::table('subscription_requests')
            ->where('instansi_id', $instansi->id)
            ->where('payment_status', 'pending')
            ->exists();

        if ($existingPendingRequest) {
            $pendingRequest = DB::table('subscription_requests')
                ->where('instansi_id', $instansi->id)
                ->where('payment_status', 'pending')
                ->first();
                
            return redirect()->route('admin.subscription.transaction', $pendingRequest->id)
                ->with('info', 'Anda memiliki permintaan subscription yang sedang diproses. Silakan selesaikan pembayaran ini terlebih dahulu.');
        }

        // Get current subscription (active or inactive)
        $currentSubscription = DB::table('subscriptions')
            ->where('instansi_id', $instansi->id)
            ->whereIn('status', ['active', 'inactive', 'expired'])
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$currentSubscription) {
            return redirect()->back()->with('error', 'Tidak ada subscription untuk diperpanjang. Silakan hubungi superadmin untuk membuat subscription baru.');
        }

        // Prevent trial subscriptions from being extended
        if ($currentSubscription->is_trial || $currentSubscription->price == 0) {
            return redirect()->back()->with('error', 'Paket trial tidak dapat diperpanjang. Silakan upgrade ke paket berbayar terlebih dahulu.');
        }

        // For extension, always extend from current date (when request is made)
        $extensionStartDate = now();
        $extensionMonths = (int) $request->extension_months;
        $newEndDate = $extensionStartDate->copy()->addMonths($extensionMonths);

        // Calculate price (use current subscription price)
        $totalPrice = $currentSubscription->price * $extensionMonths;

        // Create pending payment record
        $paymentId = DB::table('subscription_requests')->insertGetId([
            'instansi_id' => $instansi->id,
            'package_id' => $currentSubscription->package_id,
            'subscription_id' => $currentSubscription->id,
            'extension_months' => $request->extension_months,
            'target_package_id' => null,
            'amount' => $totalPrice,
            'payment_method' => 'pending',
            'payment_status' => 'pending',
            'transaction_id' => 'EXT-' . $currentSubscription->id . '-' . time(),
            'notes' => "Perpanjangan subscription {$request->extension_months} bulan. " . ($request->notes ?? ''),
            'created_by' => $user->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Redirect to transaction page instead of showing success message
        return redirect()->route('admin.subscription.transaction', $paymentId)->with('success', 'Permintaan perpanjangan subscription telah dibuat. Silakan lengkapi pembayaran.');
    }

    /**
     * Request subscription upgrade
     */
    public function requestUpgrade(Request $request)
    {
        $this->checkAccess();
        $request->validate([
            'target_package_id' => 'required|exists:packages,id',
            'notes' => 'nullable|string|max:500'
        ]);

        $user = auth()->user();
        
        if (!$user->instansi) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda tidak terhubung dengan Instansi manapun.');
        }

        $instansi = $user->instansi->load('package');

        // Check if there are already pending requests
        $existingPendingRequest = DB::table('subscription_requests')
            ->where('instansi_id', $instansi->id)
            ->where('payment_status', 'pending')
            ->exists();

        if ($existingPendingRequest) {
            $pendingRequest = DB::table('subscription_requests')
                ->where('instansi_id', $instansi->id)
                ->where('payment_status', 'pending')
                ->first();
                
            return redirect()->route('admin.subscription.transaction', $pendingRequest->id)
                ->with('info', 'Anda memiliki permintaan subscription yang sedang diproses. Silakan selesaikan pembayaran ini terlebih dahulu.');
        }

        // Get current subscription (active or inactive)
        $currentSubscription = DB::table('subscriptions')
            ->where('instansi_id', $instansi->id)
            ->whereIn('status', ['active', 'inactive', 'expired'])
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$currentSubscription) {
            // Handle New Subscription
            $targetPackage = DB::table('packages')->find($request->target_package_id);
            
            if (!$targetPackage) {
                 return redirect()->back()->with('error', 'Paket tidak ditemukan.');
            }

            $paymentId = DB::table('subscription_requests')->insertGetId([
                'instansi_id' => $instansi->id,
                'package_id' => $targetPackage->id,
                'subscription_id' => null,
                'extension_months' => null,
                'target_package_id' => $targetPackage->id,
                'amount' => $targetPackage->price,
                'payment_method' => 'pending',
                'payment_status' => 'pending',
                'transaction_id' => 'NEW-' . $instansi->id . '-' . time(),
                'notes' => "Pembelian paket baru {$targetPackage->name}. " . ($request->notes ?? ''),
                'created_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            return redirect()->route('admin.subscription.transaction', $paymentId)
                ->with('success', 'Permintaan subscription baru berhasil dibuat. Silakan lakukan pembayaran.');

            return redirect()->route('admin.subscription.transaction', $paymentId)
                ->with('success', 'Permintaan subscription baru berhasil dibuat. Silakan lakukan pembayaran.');
        }

        $targetPackage = DB::table('packages')->find($request->target_package_id);

        // Calculate price difference (upgrade cost)
        // Get the current package price from packages table to ensure we have the latest price
        $currentPackage = DB::table('packages')->find($currentSubscription->package_id);
        
        $priceDifference = $targetPackage->price - $currentPackage->price;
        if ($priceDifference < 0) {
            $priceDifference = 0; // No charge for downgrade
        }

        // Create pending payment record for upgrade
        $paymentId = DB::table('subscription_requests')->insertGetId([
            'instansi_id' => $instansi->id,
            'package_id' => $currentSubscription->package_id,
            'subscription_id' => $currentSubscription->id,
            'extension_months' => null,
            'target_package_id' => $request->target_package_id,
            'amount' => $priceDifference,
            'payment_method' => 'pending',
            'payment_status' => 'pending',
            'transaction_id' => 'UPG-' . $currentSubscription->id . '-' . time(),
            'notes' => "Upgrade dari paket " . ($instansi->package->name ?? 'N/A') . " ke paket {$targetPackage->name}. " . ($request->notes ?? ''),
            'created_by' => $user->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Redirect to transaction page instead of showing success message
        return redirect()->route('admin.subscription.transaction', $paymentId)->with('success', 'Permintaan upgrade subscription telah dibuat. Silakan lengkapi pembayaran.');
    }

    /**
     * Process pending payment (extension/upgrade)
     */
    public function processPayment($paymentId)
    {
        $user = auth()->user();
        $payment = DB::table('subscription_requests')
            ->where('id', $paymentId)
            ->where('instansi_id', $user->instansi_id)
            ->where('payment_status', 'pending')
            ->first();

        if (!$payment) {
            return redirect()->back()->with('error', 'Payment tidak ditemukan atau sudah diproses.');
        }

        // Get current subscription
        $subscription = DB::table('subscriptions')
            ->where('id', $payment->subscription_id)
            ->where('instansi_id', $user->instansi_id)
            ->first();

        if (!$subscription) {
            return redirect()->back()->with('error', 'Subscription tidak ditemukan.');
        }

        // Redirect to edit subscription with pre-filled data
        return redirect()->route('admin.subscription.edit', $subscription->id)
            ->with('pending_payment', $payment);
    }

    /**
     * Cancel pending payment
     */
    public function cancelPayment($paymentId)
    {
        $user = auth()->user();
        $instansi = $user->instansi;

        if (!$instansi) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda tidak terhubung dengan Instansi manapun.');
        }

        $updated = DB::table('subscription_requests')
            ->where('id', $paymentId)
            ->where('instansi_id', $user->instansi_id)
            ->where('payment_status', 'pending')
            ->update([
                'payment_status' => 'cancelled',
                'updated_at' => now()
            ]);

        if ($updated) {
            // Check if the current subscription is pending (meaning they just registered and haven't paid)
            // We check the subscription associated with this request or just the latest pending one
            $pendingSubscription = \App\Models\SuperAdmin\Subscription::where('instansi_id', $instansi->id)
                ->where('status', 'pending')
                ->first();

            if ($pendingSubscription) {
                // Downgrade to TRIAL
                $trialPackage = \App\Models\SuperAdmin\Package::where('name', 'TRIAL')->first();
                
                if ($trialPackage) {
                    $pendingSubscription->update([
                        'package_id' => $trialPackage->id,
                        'status' => 'active',
                        'price' => 0,
                        'payment_status' => 'paid', // Free trial is considered paid/active
                        'is_trial' => true,
                        'trial_ends_at' => now()->addDays($trialPackage->duration_days),
                        'start_date' => now(),
                        'end_date' => now()->addDays($trialPackage->duration_days),
                    ]);

                    // Update Instansi
                    $instansi->update([
                        'package_id' => $trialPackage->id,
                        'subscription_start' => now(),
                        'subscription_end' => now()->addDays($trialPackage->duration_days),
                        'status_langganan' => 'active',
                        'max_employees' => $trialPackage->max_employees,
                    ]);
                    
                    return redirect()->route('dashboard')->with('success', 'Pembayaran dibatalkan. Anda sekarang menggunakan paket TRIAL.');
                }
            }

            return redirect()->back()->with('success', 'Permintaan pembayaran telah dibatalkan.');
        }

        return redirect()->back()->with('error', 'Gagal membatalkan permintaan pembayaran.');
    }

    /**
     * Show the form for editing subscription
     */
    public function edit($id)
    {
        $user = auth()->user();
        $subscription = DB::table('subscriptions')
            ->leftJoin('packages', 'subscriptions.package_id', '=', 'packages.id')
            ->where('subscriptions.id', $id)
            ->where('subscriptions.instansi_id', $user->instansi_id)
            ->select('subscriptions.*', 'packages.name as package_name')
            ->first();

        if (!$subscription) {
            return redirect()->route('admin.subscription.index')->with('error', 'Subscription tidak ditemukan.');
        }

        $packages = DB::table('packages')
            ->where('is_active', true)
            ->get();

        // Check if there's pending payment data from session
        $pendingPayment = session('pending_payment');

        return view('admin.subscription.edit', compact('subscription', 'packages', 'pendingPayment'));
    }

    /**
     * Update subscription with payment processing
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $subscription = DB::table('subscriptions')
            ->where('id', $id)
            ->where('instansi_id', $user->instansi_id)
            ->first();

        if (!$subscription) {
            return redirect()->route('admin.subscription.index')->with('error', 'Subscription tidak ditemukan.');
        }

        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'price' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:transfer,bank_transfer,cash,credit_card',
        ]);

        // Check if this is processing a pending payment
        $pendingPayment = session('pending_payment');
        if ($pendingPayment && $pendingPayment->id) {
            // Update payment status to paid and set payment method
            DB::table('subscription_requests')
                ->where('id', $pendingPayment->id)
                ->update([
                    'payment_status' => 'paid',
                    'payment_method' => $validated['payment_method'],
                    'updated_at' => now()
                ]);

            // Clear session
            session()->forget('pending_payment');
        }

        // Update subscription
        DB::table('subscriptions')
            ->where('id', $id)
            ->update([
                'package_id' => $validated['package_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'price' => $validated['price'] ?? $subscription->price,
                'updated_at' => now()
            ]);

        return redirect()->route('admin.subscription.index')
            ->with('success', 'Subscription berhasil diperbarui.');
    }

    /**
     * Show upgrade page
     */
    public function showUpgrade()
    {
        return redirect()->route('admin.subscription.index')->with('info', 'Silakan pilih paket yang ingin Anda upgrade.');
    }

    /**
     * Show extend page
     */
    public function showExtend()
    {
        return redirect()->route('admin.subscription.index')->with('info', 'Silakan pilih durasi perpanjangan paket Anda.');
    }
}
