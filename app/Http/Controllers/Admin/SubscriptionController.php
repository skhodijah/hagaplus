<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    /**
     * Display the subscription management page for admin
     */
    public function index()
    {
        $user = auth()->user();
        $instansi = $user->instansi;

        // Get current subscription
        $currentSubscription = DB::table('subscriptions')
            ->leftJoin('packages', 'subscriptions.package_id', '=', 'packages.id')
            ->where('subscriptions.instansi_id', $instansi->id)
            ->where('subscriptions.status', 'active')
            ->select('subscriptions.*', 'packages.name as package_name')
            ->first();

        // Get subscription history
        $subscriptionHistory = DB::table('subscriptions')
            ->leftJoin('packages', 'subscriptions.package_id', '=', 'packages.id')
            ->where('subscriptions.instansi_id', $instansi->id)
            ->select('subscriptions.*', 'packages.name as package_name')
            ->orderBy('subscriptions.created_at', 'desc')
            ->get();

        // Get payment history
        $paymentHistory = DB::table('payment_history')
            ->leftJoin('packages', 'payment_history.package_id', '=', 'packages.id')
            ->where('payment_history.instansi_id', $instansi->id)
            ->select('payment_history.*', 'packages.name as package_name')
            ->orderBy('payment_history.created_at', 'desc')
            ->get();

        // Get available packages
        $packages = DB::table('packages')
            ->where('is_active', true)
            ->get();

        return view('admin.subscription.index', compact(
            'currentSubscription',
            'subscriptionHistory',
            'paymentHistory',
            'packages',
            'instansi'
        ));
    }

    /**
     * Unified request method for extension, upgrade, or both
     */
    public function handleRequest(Request $request)
    {
        $request->validate([
            'request_type' => 'required|in:extension,upgrade,both',
            'extension_months' => 'required_if:request_type,extension|required_if:request_type,both|integer|min:1|max:12',
            'target_package_id' => 'required_if:request_type,upgrade|required_if:request_type,both|exists:packages,id',
            'notes' => 'nullable|string|max:500'
        ]);

        $user = auth()->user();
        $instansi = $user->instansi;

        // Check if there are already pending requests
        $existingPendingRequest = DB::table('payment_history')
            ->where('instansi_id', $instansi->id)
            ->where('payment_status', 'pending')
            ->exists();

        if ($existingPendingRequest) {
            return redirect()->back()->with('error', 'Anda sudah memiliki permintaan subscription yang sedang diproses. Harap tunggu sampai permintaan sebelumnya selesai atau dibatalkan.');
        }

        // Get current subscription (active or inactive)
        $currentSubscription = DB::table('subscriptions')
            ->where('instansi_id', $instansi->id)
            ->whereIn('status', ['active', 'inactive', 'expired'])
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$currentSubscription) {
            return redirect()->back()->with('error', 'Tidak ada subscription untuk dikelola. Silakan hubungi superadmin untuk membuat subscription baru.');
        }

        $requestType = $request->request_type;
        $totalAmount = 0;
        $notes = '';
        $transactionPrefix = '';

        if ($requestType === 'extension') {
            // Calculate extension cost
            $extensionMonths = (int) $request->extension_months;
            $totalAmount = $currentSubscription->price * $extensionMonths;
            $notes = "Perpanjangan subscription {$extensionMonths} bulan. " . ($request->notes ?? '');
            $transactionPrefix = 'EXT';
            $whatsappDetails = "perpanjangan subscription {$extensionMonths} bulan";

            // Create pending payment record
            $paymentId = DB::table('payment_history')->insertGetId([
                'instansi_id' => $instansi->id,
                'package_id' => $currentSubscription->package_id,
                'subscription_id' => $currentSubscription->id,
                'amount' => $totalAmount,
                'payment_method' => 'pending',
                'payment_status' => 'pending',
                'transaction_id' => $transactionPrefix . '-' . $currentSubscription->id . '-' . time(),
                'notes' => $notes,
                'created_by' => $user->id,
                'start_date' => $currentSubscription->end_date,
                'end_date' => \Carbon\Carbon::parse($currentSubscription->end_date)->addMonths($extensionMonths)->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now()
            ]);

        } elseif ($requestType === 'upgrade') {
            // Calculate upgrade cost
            $targetPackage = DB::table('packages')->find($request->target_package_id);
            $priceDifference = $targetPackage->price - $currentSubscription->price;
            $totalAmount = max(0, $priceDifference); // No charge for downgrade
            $notes = "Upgrade dari paket " . ($instansi->package->name ?? 'N/A') . " ke paket {$targetPackage->name}. " . ($request->notes ?? '');
            $transactionPrefix = 'UPG';
            $whatsappDetails = "upgrade subscription dari paket " . ($instansi->package->name ?? 'N/A') . " ke paket {$targetPackage->name}";

            // Create pending payment record
            $paymentId = DB::table('payment_history')->insertGetId([
                'instansi_id' => $instansi->id,
                'package_id' => $request->target_package_id,
                'subscription_id' => $currentSubscription->id,
                'amount' => $totalAmount,
                'payment_method' => 'pending',
                'payment_status' => 'pending',
                'transaction_id' => $transactionPrefix . '-' . $currentSubscription->id . '-' . time(),
                'notes' => $notes,
                'created_by' => $user->id,
                'start_date' => $currentSubscription->start_date,
                'end_date' => $currentSubscription->end_date,
                'created_at' => now(),
                'updated_at' => now()
            ]);

        } elseif ($requestType === 'both') {
            // Calculate both extension and upgrade cost
            $extensionMonths = (int) $request->extension_months;
            $targetPackage = DB::table('packages')->find($request->target_package_id);

            // Extension cost
            $extensionCost = $currentSubscription->price * $extensionMonths;

            // Upgrade cost (full difference, not prorated)
            $upgradeCost = max(0, $targetPackage->price - $currentSubscription->price);

            $totalAmount = $extensionCost + $upgradeCost;
            $notes = "Perpanjangan {$extensionMonths} bulan + Upgrade dari paket " . ($instansi->package->name ?? 'N/A') . " ke paket {$targetPackage->name}. Biaya perpanjangan: Rp " . number_format($extensionCost, 0, ',', '.') . ", Biaya upgrade: Rp " . number_format($upgradeCost, 0, ',', '.') . ". " . ($request->notes ?? '');
            $transactionPrefix = 'BOTH';
            $whatsappDetails = "perpanjangan subscription {$extensionMonths} bulan + upgrade dari paket " . ($instansi->package->name ?? 'N/A') . " ke paket {$targetPackage->name}";

            // Create pending payment record
            $paymentId = DB::table('payment_history')->insertGetId([
                'instansi_id' => $instansi->id,
                'package_id' => $request->target_package_id,
                'subscription_id' => $currentSubscription->id,
                'amount' => $totalAmount,
                'payment_method' => 'pending',
                'payment_status' => 'pending',
                'transaction_id' => $transactionPrefix . '-' . $currentSubscription->id . '-' . time(),
                'notes' => $notes,
                'created_by' => $user->id,
                'start_date' => $currentSubscription->end_date,
                'end_date' => \Carbon\Carbon::parse($currentSubscription->end_date)->addMonths($extensionMonths)->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Create notification for superadmin
        DB::table('notifications')->insert([
            'user_id' => null, // null means for all superadmins
            'type' => 'subscription_request',
            'title' => 'Permintaan ' . ucfirst($requestType) . ' Subscription',
            'message' => "Instansi {$instansi->nama_instansi} mengajukan " . ($requestType === 'extension' ? 'perpanjangan' : ($requestType === 'upgrade' ? 'upgrade' : 'perpanjangan + upgrade')) . " subscription - " . route('superadmin.subscriptions.subscription-requests'),
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create WhatsApp message with detailed information
        $whatsappMessage = urlencode("Halo Admin, saya telah mengajukan {$whatsappDetails} untuk instansi {$instansi->nama_instansi}. Transaction ID: {$paymentId}. Jumlah: Rp " . number_format($totalAmount, 0, ',', '.') . ". Mohon segera diproses. Terima kasih!");

        // Redirect to WhatsApp (you may need to configure the WhatsApp number)
        $whatsappUrl = "https://wa.me/6281234567890?text={$whatsappMessage}"; // Replace with actual WhatsApp number

        return redirect($whatsappUrl);
    }

    /**
     * Request subscription extension
     */
    public function requestExtension(Request $request)
    {
        $request->validate([
            'extension_months' => 'required|integer|min:1|max:12',
            'notes' => 'nullable|string|max:500'
        ]);

        $user = auth()->user();
        $instansi = $user->instansi->load('package');

        // Check if there are already pending requests
        $existingPendingRequest = DB::table('payment_history')
            ->where('instansi_id', $instansi->id)
            ->where('payment_status', 'pending')
            ->exists();

        if ($existingPendingRequest) {
            return redirect()->back()->with('error', 'Anda sudah memiliki permintaan subscription yang sedang diproses. Harap tunggu sampai permintaan sebelumnya selesai atau dibatalkan.');
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

        // Calculate new end date
        $currentEndDate = \Carbon\Carbon::parse($currentSubscription->end_date);
        $extensionMonths = (int) $request->extension_months;
        $newEndDate = $currentEndDate->copy()->addMonths($extensionMonths);

        // Calculate price (use current subscription price)
        $totalPrice = $currentSubscription->price * $extensionMonths;

        // Create pending payment record
        $paymentId = DB::table('payment_history')->insertGetId([
            'instansi_id' => $instansi->id,
            'package_id' => $currentSubscription->package_id,
            'subscription_id' => $currentSubscription->id,
            'amount' => $totalPrice,
            'payment_method' => 'pending',
            'payment_status' => 'pending',
            'transaction_id' => 'EXT-' . $currentSubscription->id . '-' . time(),
            'notes' => "Perpanjangan subscription {$request->extension_months} bulan. " . ($request->notes ?? ''),
            'created_by' => $user->id,
            'start_date' => $currentSubscription->end_date,
            'end_date' => $newEndDate->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Create notification for superadmin
        DB::table('notifications')->insert([
            'user_id' => null, // null means for all superadmins
            'type' => 'subscription_request',
            'title' => 'Permintaan Perpanjangan Subscription',
            'message' => "Instansi {$instansi->nama_instansi} mengajukan perpanjangan subscription {$request->extension_months} bulan - " . route('superadmin.subscriptions.subscription-requests'),
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Return success message - request has been created and superadmin will be notified
        return redirect()->back()->with('success', 'Permintaan perpanjangan subscription telah berhasil diajukan. Superadmin akan segera memproses permintaan Anda.');
    }

    /**
     * Request subscription upgrade
     */
    public function requestUpgrade(Request $request)
    {
        $request->validate([
            'target_package_id' => 'required|exists:packages,id',
            'notes' => 'nullable|string|max:500'
        ]);

        $user = auth()->user();
        $instansi = $user->instansi;

        // Check if there are already pending requests
        $existingPendingRequest = DB::table('payment_history')
            ->where('instansi_id', $instansi->id)
            ->where('payment_status', 'pending')
            ->exists();

        if ($existingPendingRequest) {
            return redirect()->back()->with('error', 'Anda sudah memiliki permintaan subscription yang sedang diproses. Harap tunggu sampai permintaan sebelumnya selesai atau dibatalkan.');
        }

        // Get current subscription (active or inactive)
        $currentSubscription = DB::table('subscriptions')
            ->where('instansi_id', $instansi->id)
            ->whereIn('status', ['active', 'inactive', 'expired'])
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$currentSubscription) {
            return redirect()->back()->with('error', 'Tidak ada subscription untuk diupgrade. Silakan hubungi superadmin untuk membuat subscription baru.');
        }

        $targetPackage = DB::table('packages')->find($request->target_package_id);

        // Calculate price difference (upgrade cost)
        $priceDifference = $targetPackage->price - $currentSubscription->price;
        if ($priceDifference < 0) {
            $priceDifference = 0; // No charge for downgrade
        }

        // Create pending payment record for upgrade
        $paymentId = DB::table('payment_history')->insertGetId([
            'instansi_id' => $instansi->id,
            'package_id' => $request->target_package_id, // Target package
            'subscription_id' => $currentSubscription->id,
            'amount' => $priceDifference,
            'payment_method' => 'pending',
            'payment_status' => 'pending',
            'transaction_id' => 'UPG-' . $currentSubscription->id . '-' . time(),
            'notes' => "Upgrade dari paket " . ($instansi->package->name ?? 'N/A') . " ke paket {$targetPackage->name}. " . ($request->notes ?? ''),
            'created_by' => $user->id,
            'start_date' => $currentSubscription->start_date,
            'end_date' => $currentSubscription->end_date,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Create notification for superadmin
        DB::table('notifications')->insert([
            'user_id' => null, // null means for all superadmins
            'type' => 'subscription_request',
            'title' => 'Permintaan Upgrade Subscription',
            'message' => "Instansi {$instansi->nama_instansi} mengajukan upgrade dari paket " . ($instansi->package->name ?? 'N/A') . " ke paket {$targetPackage->name} - " . route('superadmin.subscriptions.subscription-requests'),
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Return success message - request has been created and superadmin will be notified
        return redirect()->back()->with('success', 'Permintaan upgrade subscription telah berhasil diajukan. Superadmin akan segera memproses permintaan Anda.');
    }

    /**
     * Process pending payment (extension/upgrade)
     */
    public function processPayment($paymentId)
    {
        $user = auth()->user();
        $payment = DB::table('payment_history')
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

        $updated = DB::table('payment_history')
            ->where('id', $paymentId)
            ->where('instansi_id', $user->instansi_id)
            ->where('payment_status', 'pending')
            ->update([
                'payment_status' => 'cancelled',
                'updated_at' => now()
            ]);

        if ($updated) {
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
            DB::table('payment_history')
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
}
