<x-superadmin-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Fancybox.bind("[data-fancybox]", {
                // Your custom options
            });
        });
    </script>
    <div class="py-8" x-data="{ showApproveModal: false, showRejectModal: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Review Transaction</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Transaction ID: <span class="font-mono font-medium text-gray-700 dark:text-gray-300">{{ $subscriptionRequest->transaction_id }}</span></p>
                </div>
                <div class="mt-4 md:mt-0 flex space-x-3">
                    <a href="{{ route('superadmin.subscriptions.subscription-requests') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Back
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Request Details Card -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Request Details</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Instansi</label>
                                <div class="mt-1 flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-lg mr-3">
                                        {{ substr($subscriptionRequest->nama_instansi, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $subscriptionRequest->nama_instansi }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Requested by {{ $subscriptionRequest->created_by_name }}</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Package</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $subscriptionRequest->package_name }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</label>
                                <p class="mt-1 text-2xl font-bold text-green-600 dark:text-green-400">Rp {{ number_format($subscriptionRequest->amount, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($subscriptionRequest->created_at)->format('d F Y, H:i') }}</p>
                            </div>
                        </div>
                        @if($subscriptionRequest->notes)
                            <div class="px-6 pb-6">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Notes</label>
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-md p-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $subscriptionRequest->notes }}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Payment Proof Card -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Payment Proof</h3>
                        </div>
                        <div class="p-6 flex justify-center bg-gray-100 dark:bg-gray-900">
                            @if($subscriptionRequest->payment_proof)
                                <a href="{{ asset('storage/' . $subscriptionRequest->payment_proof) }}" data-fancybox class="relative group">
                                    <img src="{{ asset('storage/' . $subscriptionRequest->payment_proof) }}" alt="Payment Proof" class="max-h-[500px] rounded-lg shadow-lg">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center rounded-lg">
                                        <span class="opacity-0 group-hover:opacity-100 text-white font-medium px-4 py-2 bg-black bg-opacity-50 rounded-full backdrop-blur-sm transition-all duration-300">
                                            <i class="fa-solid fa-expand mr-2"></i> View Full Size
                                        </span>
                                    </div>
                                </a>
                            @else
                                <div class="text-center py-12">
                                    <i class="fa-solid fa-image text-gray-400 text-5xl mb-4"></i>
                                    <p class="text-gray-500 dark:text-gray-400">No payment proof uploaded</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column: Actions & Summary -->
                <div class="space-y-6">
                    <!-- Status Card -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Action Required</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                            Please review the payment proof and transaction details carefully before approving or rejecting.
                        </p>
                        
                        <div class="space-y-3">
                            <button @click="showApproveModal = true" class="w-full flex justify-center items-center px-4 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                <i class="fa-solid fa-check mr-2"></i> Approve Transaction
                            </button>
                            
                            <button @click="showRejectModal = true" class="w-full flex justify-center items-center px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-red-600 dark:text-red-400 bg-white dark:bg-gray-800 hover:bg-red-50 dark:hover:bg-red-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                <i class="fa-solid fa-xmark mr-2"></i> Reject Transaction
                            </button>
                        </div>
                    </div>

                    <!-- Payment Method Info -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white uppercase tracking-wider mb-4">Payment Method</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-500 dark:text-gray-400">Method</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $subscriptionRequest->payment_method_name }}</span>
                            </div>
                            @if($subscriptionRequest->bank_name)
                            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-500 dark:text-gray-400">Bank</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $subscriptionRequest->bank_name }}</span>
                            </div>
                            @endif
                            @if($subscriptionRequest->account_number)
                            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-500 dark:text-gray-400">Account No</span>
                                <span class="font-medium text-gray-900 dark:text-white font-mono">{{ $subscriptionRequest->account_number }}</span>
                            </div>
                            @endif
                            @if($subscriptionRequest->account_name)
                            <div class="flex justify-between py-2">
                                <span class="text-gray-500 dark:text-gray-400">Account Name</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $subscriptionRequest->account_name }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approve Modal -->
        <div x-show="showApproveModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showApproveModal" @click="showApproveModal = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showApproveModal" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fa-solid fa-check text-green-600 dark:text-green-400"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                    Approve Transaction
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Are you sure you want to approve this transaction? This will verify the payment and activate/extend the subscription for <strong>{{ $subscriptionRequest->nama_instansi }}</strong>.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <form method="POST" action="{{ route('superadmin.subscriptions.process-transaction.approve', $subscriptionRequest->id) }}">
                            @csrf
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Yes, Approve
                            </button>
                        </form>
                        <button @click="showApproveModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <div x-show="showRejectModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showRejectModal" @click="showRejectModal = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showRejectModal" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form method="POST" action="{{ route('superadmin.subscriptions.process-transaction.reject', $subscriptionRequest->id) }}">
                        @csrf
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="fa-solid fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                        Reject Transaction
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                            Please provide a reason for rejecting this transaction. This will be sent to the user.
                                        </p>
                                        <textarea name="rejection_reason" rows="3" class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md" placeholder="Reason for rejection..." required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Reject Transaction
                            </button>
                            <button @click="showRejectModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-superadmin-layout>