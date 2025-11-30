<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    /**
     * Show the transaction page for a subscription request
     */
    public function show($requestId)
    {
        $user = auth()->user();

        // Get the subscription request
        $subscriptionRequest = DB::table('subscription_requests')
            ->leftJoin('packages as current_package', 'subscription_requests.package_id', '=', 'current_package.id')
            ->leftJoin('packages as target_package', 'subscription_requests.target_package_id', '=', 'target_package.id')
            ->where('subscription_requests.id', $requestId)
            ->where('subscription_requests.instansi_id', $user->instansi_id)
            ->select(
                'subscription_requests.*', 
                'current_package.name as current_package_name',
                'target_package.name as target_package_name',
                DB::raw('COALESCE(target_package.name, current_package.name) as package_name')
            )
            ->first();

        if (!$subscriptionRequest) {
            return redirect()->route('admin.subscription.index')->with('error', 'Subscription request not found.');
        }

        // Get available active payment methods
        $paymentMethods = PaymentMethod::active()->get();

        return view('admin.subscription.transaction', compact('subscriptionRequest', 'paymentMethods'));
    }

    /**
     * Process payment proof upload
     */
    public function processPayment(Request $request, $requestId)
    {
        $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        $user = auth()->user();

        // Get the subscription request
        $subscriptionRequest = DB::table('subscription_requests')
            ->where('id', $requestId)
            ->where('instansi_id', $user->instansi_id)
            ->where('payment_status', 'pending')
            ->first();

        if (!$subscriptionRequest) {
            return redirect()->route('admin.subscription.index')->with('error', 'Subscription request not found or already processed.');
        }

        // Handle file upload
        $file = $request->file('payment_proof');
        $filename = 'payment_proof_' . $requestId . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('payment-proofs', $filename, 'public');

        // Update subscription request with payment proof
        DB::table('subscription_requests')
            ->where('id', $requestId)
            ->update([
                'payment_method_id' => $request->payment_method_id,
                'payment_proof' => $path,
                'payment_proof_uploaded_at' => now(),
                'updated_at' => now()
            ]);

        return redirect()->route('admin.subscription.index')->with('success', 'Bukti pembayaran berhasil diupload. Tim kami akan memproses permintaan Anda dalam waktu 1-2 hari kerja.');
    }
}
