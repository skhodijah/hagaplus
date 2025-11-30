<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionProcessingController extends Controller
{
    /**
     * Show the transaction processing page
     */
    public function show($requestId)
    {
        // Get the subscription request with payment proof
        $subscriptionRequest = DB::table('subscription_requests')
            ->leftJoin('instansis', 'subscription_requests.instansi_id', '=', 'instansis.id')
            ->leftJoin('packages', 'subscription_requests.package_id', '=', 'packages.id')
            ->leftJoin('packages as target_packages', 'subscription_requests.target_package_id', '=', 'target_packages.id')
            ->leftJoin('payment_methods', 'subscription_requests.payment_method_id', '=', 'payment_methods.id')
            ->leftJoin('users', 'subscription_requests.created_by', '=', 'users.id')
            ->where('subscription_requests.id', $requestId)
            ->whereNotNull('subscription_requests.payment_proof')
            ->select(
                'subscription_requests.*',
                'instansis.nama_instansi',
                'packages.name as package_name',
                'target_packages.name as target_package_name',
                'payment_methods.name as payment_method_name',
                'payment_methods.account_number',
                'payment_methods.account_name',
                'payment_methods.bank_name',
                'users.name as created_by_name'
            )
            ->first();

        if (!$subscriptionRequest) {
            return redirect()->route('superadmin.subscriptions.subscription-requests')->with('error', 'Transaction request not found.');
        }

        // Get current subscription for comparison
        $currentSubscription = DB::table('subscriptions')
            ->leftJoin('packages', 'subscriptions.package_id', '=', 'packages.id')
            ->where('subscriptions.id', $subscriptionRequest->subscription_id)
            ->select('subscriptions.*', 'packages.name as current_package_name')
            ->first();

        return view('superadmin.subscriptions.process-transaction', compact('subscriptionRequest', 'currentSubscription'));
    }

    /**
     * Approve the transaction and update subscription
     */
    public function approve(Request $request, $requestId)
    {
        \Log::info('Approve method called', ['request_id' => $requestId, 'method' => $request->method(), 'all_data' => $request->all()]);

        // Start transaction
        DB::beginTransaction();

        try {
            // First check if request exists with payment proof
            $subscriptionRequest = DB::table('subscription_requests')
                ->where('id', $requestId)
                ->whereNotNull('payment_proof')
                ->first();

            if (!$subscriptionRequest) {
                return redirect()->route('superadmin.subscriptions.subscription-requests')
                    ->with('error', 'Transaction request not found or no payment proof uploaded.');
            }

            // Check if already processed
            if ($subscriptionRequest->payment_status !== 'pending') {
                return redirect()->route('superadmin.subscriptions.subscription-requests')
                    ->with('error', 'Transaction request has already been processed.');
            }

            // Debug: Log the subscription request details
            \Log::info('Processing subscription approval', [
                'request_id' => $requestId,
                'subscription_id' => $subscriptionRequest->subscription_id,
                'notes' => $subscriptionRequest->notes
            ]);

            // Update subscription if subscription_id is provided
            if ($subscriptionRequest->subscription_id) {
                // Get the current subscription
                $subscription = DB::table('subscriptions')
                    ->where('id', $subscriptionRequest->subscription_id)
                    ->first();

                if (!$subscription) {
                    throw new \Exception('Subscription not found');
                }

                // Update subscription based on the request
                $subscriptionUpdate = [
                    'status' => 'active',
                    'updated_at' => now()
                ];

                // Handle subscription extension
                if ($subscriptionRequest->extension_months) {
                    $currentDate = now();
                    $currentEndDate = \Carbon\Carbon::parse($subscription->end_date);
                    
                    // If subscription has already expired, extend from current date
                    // Otherwise, extend from the current end date
                    $startDate = $currentDate->gt($currentEndDate) ? $currentDate : $currentEndDate;
                    
                    $newEndDate = $startDate->copy()
                        ->addMonths($subscriptionRequest->extension_months)
                        ->format('Y-m-d');
                        
                    $subscriptionUpdate['end_date'] = $newEndDate;
                    
                    // Log the extension details
                    \Log::info('Extending subscription', [
                        'current_end_date' => $currentEndDate->format('Y-m-d'),
                        'current_date' => $currentDate->format('Y-m-d'),
                        'extension_months' => $subscriptionRequest->extension_months,
                        'new_end_date' => $newEndDate,
                        'extended_from_expired' => $currentDate->gt($currentEndDate)
                    ]);
                }

                if ($subscriptionRequest->target_package_id) {
                    $targetPackage = DB::table('packages')
                        ->where('id', $subscriptionRequest->target_package_id)
                        ->first();

                    if ($targetPackage) {
                        $subscriptionUpdate['package_id'] = $targetPackage->id;
                        $subscriptionUpdate['price'] = $targetPackage->price;
                    }
                }

                // Log the updates
                \Log::info('Updating subscription', [
                    'subscription_id' => $subscription->id,
                    'updates' => $subscriptionUpdate
                ]);

                // Update subscription
                $updated = DB::table('subscriptions')
                    ->where('id', $subscription->id)
                    ->update($subscriptionUpdate);

                if ($updated === false) {
                    throw new \Exception('Failed to update subscription');
                }
            } else {
                // Create New Subscription
                $package = DB::table('packages')->find($subscriptionRequest->package_id);

                if (!$package) {
                    throw new \Exception('Package not found');
                }

                $subscriptionId = DB::table('subscriptions')->insertGetId([
                    'instansi_id' => $subscriptionRequest->instansi_id,
                    'package_id' => $package->id,
                    'package_name' => $package->name,
                    'price' => $subscriptionRequest->amount,
                    'duration' => 1, // Default 1 month
                    'start_date' => now(),
                    'end_date' => now()->addMonth(),
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update request with new subscription ID
                DB::table('subscription_requests')->where('id', $requestId)->update(['subscription_id' => $subscriptionId]);
                
                // Update Instansi status
                DB::table('instansis')->where('id', $subscriptionRequest->instansi_id)->update([
                    'status_langganan' => 'active',
                    'package_id' => $package->id,
                    'subscription_start' => now(),
                    'subscription_end' => now()->addMonth(),
                    'updated_at' => now()
                ]);

                \Log::info('Created new subscription', ['subscription_id' => $subscriptionId]);
            }

            // Update payment status to paid
            $updatedRequest = DB::table('subscription_requests')
                ->where('id', $requestId)
                ->update([
                    'payment_status' => 'paid',
                    'processed_at' => now(),
                    'updated_at' => now()
                ]);

            if ($updatedRequest === false) {
                throw new \Exception('Failed to update subscription request');
            }

            // Create notification for the instansi admin
            DB::table('notifications')->insert([
                'user_id' => $subscriptionRequest->created_by,
                'type' => 'subscription_approved',
                'title' => 'Subscription Request Approved',
                'message' => "Your subscription request has been approved and processed successfully.",
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            \Log::info('Transaction approved successfully', ['request_id' => $requestId]);

            return redirect()->route('superadmin.subscriptions.subscription-requests')
                ->with('success', 'Transaction approved and subscription updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Transaction approval failed', [
                'request_id' => $requestId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Failed to process transaction: ' . $e->getMessage());
        }
    }

    /**
     * Reject the transaction
     */
    public function reject(Request $request, $requestId)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $subscriptionRequest = DB::table('subscription_requests')
            ->where('id', $requestId)
            ->where('payment_status', 'pending')
            ->whereNotNull('payment_proof')
            ->first();

        if (!$subscriptionRequest) {
            return redirect()->route('superadmin.subscriptions.subscription-requests')->with('error', 'Transaction request not found or already processed.');
        }

        // Update payment status to cancelled
        DB::table('subscription_requests')
            ->where('id', $requestId)
            ->update([
                'payment_status' => 'cancelled',
                'notes' => ($subscriptionRequest->notes ?? '') . "\n\nCANCELLED: " . $request->rejection_reason,
                'updated_at' => now()
            ]);

        return redirect()->route('superadmin.subscriptions.subscription-requests')->with('success', 'Transaction rejected successfully.');
    }
}
