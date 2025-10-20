<x-superadmin-layout>
    <div class="py-6 md:py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 md:mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">Process Transaction</h1>
                        <p class="mt-1 text-gray-600 dark:text-gray-400">Review and process subscription payment request</p>
                    </div>
                    <a href="{{ route('superadmin.subscriptions.subscription-requests') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fa-solid fa-arrow-left mr-2"></i>Back to Requests
                    </a>
                </div>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-lg p-4">
                    <div class="flex">
                        <i class="fa-solid fa-check-circle text-green-400 mr-3 mt-0.5"></i>
                        <p class="text-green-800 dark:text-green-200">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg p-4">
                    <div class="flex">
                        <i class="fa-solid fa-exclamation-circle text-red-400 mr-3 mt-0.5"></i>
                        <p class="text-red-800 dark:text-red-200">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Transaction Details -->
                <div class="lg:col-span-2">
                    <x-section-card title="Transaction Details">
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Transaction ID</label>
                                    <p class="text-sm text-gray-900 dark:text-white font-mono">{{ $subscriptionRequest->transaction_id }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Amount</label>
                                    <p class="text-lg font-semibold text-green-600 dark:text-green-400">Rp {{ number_format($subscriptionRequest->amount, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Instansi</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $subscriptionRequest->nama_instansi }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Package</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $subscriptionRequest->package_name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Payment Method</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $subscriptionRequest->payment_method_name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Request Date</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($subscriptionRequest->created_at)->format('d M Y H:i') }}</p>
                                </div>
                            </div>

                            @if($subscriptionRequest->notes)
                                <div class="pt-4 border-t border-gray-200 dark:border-gray-600">
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Request Details</label>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $subscriptionRequest->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </x-section-card>

                    <!-- Payment Proof -->
                    <x-section-card title="Payment Proof" class="mt-6">
                        @if($subscriptionRequest->payment_proof)
                            <div class="text-center">
                                <img src="{{ asset('storage/' . $subscriptionRequest->payment_proof) }}" alt="Payment Proof"
                                     class="max-w-full max-h-96 object-contain mx-auto border border-gray-200 dark:border-gray-600 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                    Uploaded on {{ $subscriptionRequest->payment_proof_uploaded_at ? \Carbon\Carbon::parse($subscriptionRequest->payment_proof_uploaded_at)->format('d M Y H:i') : 'N/A' }}
                                </p>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fa-solid fa-image text-gray-400 text-4xl mb-4"></i>
                                <p class="text-gray-500 dark:text-gray-400">No payment proof uploaded</p>
                            </div>
                        @endif
                    </x-section-card>

                    <!-- Current vs Requested Changes -->
                    @if($currentSubscription)
                        <x-section-card title="Subscription Changes" class="mt-6">
                            <div class="space-y-4">
                                <h4 class="font-medium text-gray-900 dark:text-white">Current Subscription</h4>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Package:</span>
                                        <span class="text-gray-900 dark:text-white ml-2">{{ $currentSubscription->current_package_name ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Price:</span>
                                        <span class="text-gray-900 dark:text-white ml-2">Rp {{ number_format($currentSubscription->price, 0, ',', '.') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Start Date:</span>
                                        <span class="text-gray-900 dark:text-white ml-2">{{ \Carbon\Carbon::parse($currentSubscription->start_date)->format('d M Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">End Date:</span>
                                        <span class="text-gray-900 dark:text-white ml-2">{{ \Carbon\Carbon::parse($currentSubscription->end_date)->format('d M Y') }}</span>
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-gray-200 dark:border-gray-600">
                                    <h4 class="font-medium text-gray-900 dark:text-white">Requested Changes</h4>
                                    <div class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                        @php
                                            $changes = [];
                                            if ($subscriptionRequest->extension_months) {
                                                $currentDate = now();
                                                $currentEndDate = \Carbon\Carbon::parse($currentSubscription->end_date);
                                                
                                                // If subscription has already expired, extend from current date
                                                // Otherwise, extend from the current end date
                                                $startDate = $currentDate->gt($currentEndDate) ? $currentDate : $currentEndDate;
                                                
                                                $newEndDate = $startDate->copy()
                                                    ->addMonths($subscriptionRequest->extension_months)
                                                    ->format('d M Y');
                                                    
                                                $changes[] = "Extend subscription by {$subscriptionRequest->extension_months} months (until {$newEndDate})";
                                            }
                                            if ($subscriptionRequest->target_package_name) {
                                                $changes[] = "Change package to: {$subscriptionRequest->target_package_name}";
                                            }
                                        @endphp

                                        @if(count($changes) > 0)
                                            <ul class="list-disc list-inside space-y-1">
                                                @foreach($changes as $change)
                                                    <li>{{ $change }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p>No specific changes requested.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </x-section-card>
                    @endif
                </div>

                <!-- Action Panel -->
                <div class="space-y-6">
                    <!-- Approve Transaction -->
                    <x-section-card title="Approve Transaction">
                        <form method="POST" action="{{ route('superadmin.subscriptions.process-transaction.approve', $subscriptionRequest->id) }}">
                            @csrf
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Approve this transaction and apply the requested changes to the subscription.
                            </p>
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                <i class="fa-solid fa-check mr-2"></i>Approve & Process
                            </button>
                        </form>
                    </x-section-card>

                    <!-- Reject Transaction -->
                    <x-section-card title="Reject Transaction">
                        <form method="POST" action="{{ route('superadmin.subscriptions.process-transaction.reject', $subscriptionRequest->id) }}">
                            @csrf
                            <div class="mb-4">
                                <label for="rejection_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Rejection Reason <span class="text-red-500">*</span>
                                </label>
                                <textarea name="rejection_reason" id="rejection_reason" rows="3"
                                          class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                                          placeholder="Please provide a reason for rejection..." required></textarea>
                            </div>
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                <i class="fa-solid fa-times mr-2"></i>Reject Transaction
                            </button>
                        </form>
                    </x-section-card>

                    <!-- Payment Method Details -->
                    @if($subscriptionRequest->payment_method_name)
                        <x-section-card title="Payment Method Details">
                            <div class="space-y-2 text-sm">
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Method:</span>
                                    <span class="text-gray-900 dark:text-white ml-2">{{ $subscriptionRequest->payment_method_name }}</span>
                                </div>
                                @if($subscriptionRequest->account_name)
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Account Name:</span>
                                        <span class="text-gray-900 dark:text-white ml-2">{{ $subscriptionRequest->account_name }}</span>
                                    </div>
                                @endif
                                @if($subscriptionRequest->account_number)
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Account Number:</span>
                                        <span class="text-gray-900 dark:text-white ml-2 font-mono">{{ $subscriptionRequest->account_number }}</span>
                                    </div>
                                @endif
                                @if($subscriptionRequest->bank_name)
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Bank:</span>
                                        <span class="text-gray-900 dark:text-white ml-2">{{ $subscriptionRequest->bank_name }}</span>
                                    </div>
                                @endif
                            </div>
                        </x-section-card>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-superadmin-layout>