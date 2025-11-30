<x-admin-layout>
    <div class="py-6" x-data="{ 
        selectedPackage: null,
        showUpgradeModal: false,
        showExtensionModal: false,
        openUpgradeModal(package) {
            this.selectedPackage = package;
            this.showUpgradeModal = true;
        }
    }">
        <x-page-header
            title="Subscription & Billing"
            subtitle="Manage your plan and billing history"
        />

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Fancybox.bind("[data-fancybox]", {
                    // Your custom options
                });
            });
        </script>

        @if (session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('info'))
            <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            {{ session('info') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($pendingRequest) && $pendingRequest)
            <div class="mb-10 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-clock text-yellow-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            You have a pending subscription request. 
                            @if($pendingRequest->payment_proof)
                                <span class="font-bold">Payment proof uploaded. Waiting for verification.</span>
                            @else
                                <a href="{{ route('admin.subscription.transaction', $pendingRequest->id) }}" class="font-bold underline hover:text-yellow-800">
                                    Click here to complete payment
                                </a>
                            @endif
                            before making new requests.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Current Plan Banner -->
        @if($currentSubscription)
            <div class="mb-10 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-lg text-white p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
                <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-32 h-32 bg-white opacity-10 rounded-full blur-xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div>
                        <div class="text-blue-100 text-sm font-medium mb-1 tracking-wider">CURRENT PLAN</div>
                        <h2 class="text-3xl font-bold mb-2">{{ $currentSubscription->package->name ?? $currentSubscription->package_name ?? 'Unknown Package' }}</h2>
                        <div class="flex flex-wrap items-center gap-4 text-blue-100 text-sm">
                            <span class="flex items-center gap-1.5 bg-white/10 px-3 py-1 rounded-full">
                                <i class="fa-solid fa-calendar-check"></i>
                                Valid until {{ \Carbon\Carbon::parse($currentSubscription->end_date)->format('d M Y') }}
                            </span>
                            <span class="flex items-center gap-1.5 bg-white/10 px-3 py-1 rounded-full">
                                <i class="fa-solid fa-tag"></i>
                                Rp {{ number_format($currentSubscription->price, 0, ',', '.') }}/mo
                            </span>
                            <span class="flex items-center gap-1.5 bg-white/10 px-3 py-1 rounded-full">
                                <i class="fa-solid fa-users"></i>
                                Max {{ $currentSubscription->package->max_employees ?? 'N/A' }} Employees
                            </span>
                            <span class="flex items-center gap-1.5 bg-white/10 px-3 py-1 rounded-full">
                                <i class="fa-solid fa-user-shield"></i>
                                Max {{ $currentSubscription->package->max_admins ?? 'N/A' }} Admins
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-3 w-full md:w-auto">
                        @if(!isset($pendingRequest) || !$pendingRequest)
                            @if(!$currentSubscription->is_trial && $currentSubscription->price > 0)
                                <button @click="showExtensionModal = true" class="flex-1 md:flex-none bg-white text-blue-600 px-6 py-2.5 rounded-xl font-bold hover:bg-blue-50 transition shadow-sm text-sm">
                                    Extend Now
                                </button>
                            @else
                                <div class="flex-1 md:flex-none bg-yellow-50 text-yellow-700 px-6 py-2.5 rounded-xl font-bold text-sm border border-yellow-200">
                                    Trial - Upgrade to Extend
                                </div>
                            @endif
                        @endif
                        <button onclick="document.getElementById('history-section').scrollIntoView({behavior: 'smooth'})" class="flex-1 md:flex-none bg-blue-800 bg-opacity-40 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-opacity-50 transition border border-blue-400 border-opacity-30 text-sm">
                            History
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Pricing Cards -->
        <div class="mb-12">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">Choose the Right Plan</h2>
                <p class="text-gray-500 dark:text-gray-400 text-lg">Flexible plans that grow with your business</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($packages as $package)
                    @php
                        $isCurrent = $currentSubscription && $currentSubscription->package_id == $package->id;
                        $isPopular = $package->name === 'PROFESSIONAL';
                    @endphp
                    <div class="relative flex flex-col bg-white dark:bg-gray-800 rounded-2xl shadow-sm border {{ $isCurrent ? 'border-blue-500 ring-2 ring-blue-500 ring-opacity-50' : ($isPopular ? 'border-indigo-500 shadow-md' : 'border-gray-200 dark:border-gray-700') }} p-6 transition hover:shadow-xl hover:-translate-y-1 duration-300">
                        @if($isPopular && !$isCurrent)
                            <div class="absolute top-0 right-0 bg-indigo-500 text-white text-[10px] font-bold px-3 py-1 rounded-bl-xl rounded-tr-xl tracking-wider">
                                POPULAR
                            </div>
                        @endif

                        <div class="mb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $package->name }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 min-h-[32px]">{{ $package->description }}</p>
                        </div>

                        <div class="mb-6 pb-6 border-b border-gray-100 dark:border-gray-700">
                            <div class="flex items-baseline">
                                <span class="text-3xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($package->price / 1000, 0) }}k</span>
                                <span class="text-gray-500 dark:text-gray-400 ml-1 text-sm">/mo</span>
                            </div>
                        </div>

                        <ul class="space-y-3 mb-8 flex-1">
                            <li class="flex items-start text-xs text-gray-600 dark:text-gray-300">
                                <div class="mt-0.5 mr-2 flex-shrink-0 w-4 h-4 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                    <i class="fa-solid fa-users text-blue-600 dark:text-blue-400 text-[8px]"></i>
                                </div>
                                <span class="leading-tight">Up to {{ $package->max_employees }} Employees</span>
                            </li>
                            <li class="flex items-start text-xs text-gray-600 dark:text-gray-300">
                                <div class="mt-0.5 mr-2 flex-shrink-0 w-4 h-4 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                    <i class="fa-solid fa-user-shield text-purple-600 dark:text-purple-400 text-[8px]"></i>
                                </div>
                                <span class="leading-tight">Up to {{ $package->max_admins }} Admins</span>
                            </li>
                            <li class="flex items-start text-xs text-gray-600 dark:text-gray-300">
                                <div class="mt-0.5 mr-2 flex-shrink-0 w-4 h-4 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                    <i class="fa-solid fa-building text-green-600 dark:text-green-400 text-[8px]"></i>
                                </div>
                                <span class="leading-tight">Up to {{ $package->max_branches }} Branches</span>
                            </li>
                        </ul>

                        <div class="mt-auto">
                            @if(isset($pendingRequest) && $pendingRequest)
                                <button disabled class="w-full py-2.5 rounded-xl font-bold text-sm bg-gray-100 text-gray-400 cursor-not-allowed border border-gray-200 dark:bg-gray-700 dark:border-gray-600">
                                    Pending Request
                                </button>
                            @elseif($isCurrent)
                                <button disabled class="w-full py-2.5 rounded-xl font-bold text-sm bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400 cursor-not-allowed border border-gray-200 dark:border-gray-600">
                                    Current Plan
                                </button>
                            @else
                                <button @click="openUpgradeModal({{ json_encode($package) }})" class="w-full py-2.5 rounded-xl font-bold text-sm {{ $isPopular ? 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-indigo-200 dark:shadow-none shadow-lg' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600' }} transition">
                                    {{ $currentSubscription && $package->price > $currentSubscription->price ? 'Upgrade' : 'Switch Plan' }}
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- History Section -->
        <div id="history-section" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Billing History</h3>
                <button class="text-sm text-blue-600 hover:text-blue-700 font-medium">Download All</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold tracking-wider">
                            <th class="px-6 py-4 rounded-tl-lg">Date</th>
                            <th class="px-6 py-4">Description</th>
                            <th class="px-6 py-4">Amount</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right rounded-tr-lg">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($paymentHistory as $payment)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition group">
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-medium">
                                    {{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y') }}
                                    <div class="text-xs text-gray-500 font-normal mt-0.5">{{ \Carbon\Carbon::parse($payment->created_at)->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $payment->package_name }}</div>
                                    @if(isset($payment->target_package_name) && $payment->target_package_name != $payment->package_name)
                                        <div class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                                            <i class="fa-solid fa-arrow-right text-gray-400 text-[10px]"></i>
                                            <span>Upgrade to {{ $payment->target_package_name }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-white">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'paid' => 'bg-green-100 text-green-700 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800',
                                            'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-400 dark:border-yellow-800',
                                            'failed' => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800',
                                            'cancelled' => 'bg-gray-100 text-gray-700 border-gray-200 dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600',
                                        ];
                                        $color = $statusColors[$payment->payment_status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                        
                                        // Check if pending verification
                                        if ($payment->payment_status == 'pending' && $payment->payment_proof) {
                                            $color = 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800';
                                        }
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium border {{ $color }}">
                                        @if($payment->payment_status == 'pending' && $payment->payment_proof)
                                            Verifying
                                        @else
                                            {{ ucfirst($payment->payment_status) }}
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($payment->payment_status == 'pending')
                                        @if($payment->payment_proof)
                                            <div class="flex flex-col items-end gap-2">
                                                <a href="{{ asset('storage/' . $payment->payment_proof) }}" data-fancybox class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center gap-1">
                                                    <i class="fa-solid fa-image"></i> View Proof
                                                </a>
                                                <form action="{{ route('admin.subscription.cancel-payment', $payment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this subscription request?');">
                                                    @csrf
                                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">Cancel Request</button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.subscription.transaction', $payment->id) }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition shadow-sm hover:shadow-md">
                                                    <span>Pay Now</span>
                                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                                </a>
                                                <form action="{{ route('admin.subscription.cancel-payment', $payment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this subscription request?');">
                                                    @csrf
                                                    <button type="submit" class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors" title="Cancel Request">
                                                        <i class="fa-solid fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @elseif($payment->payment_status == 'paid')
                                        <button class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition" title="Download Invoice">
                                            <i class="fa-solid fa-file-invoice text-lg"></i>
                                        </button>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                            <i class="fa-solid fa-receipt text-gray-400 text-2xl"></i>
                                        </div>
                                        <p class="text-base font-medium text-gray-900 dark:text-white">No billing history available</p>
                                        <p class="text-sm text-gray-500 mt-1">Your transaction history will appear here.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Upgrade Modal -->
        <div x-show="showUpgradeModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" @click="showUpgradeModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('admin.subscription.upgrade') }}" method="POST">
                        @csrf
                        <input type="hidden" name="target_package_id" :value="selectedPackage?.id">
                        
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900/30 sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="fa-solid fa-arrow-up text-indigo-600 dark:text-indigo-400"></i>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white" id="modal-title">
                                        Switch to <span x-text="selectedPackage?.name"></span>
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            You are about to switch your subscription plan. The new price will be <span class="font-bold text-gray-900 dark:text-white" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(selectedPackage?.price)"></span>/month.
                                        </p>
                                        
                                        <div class="mt-4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Additional Notes (Optional)</label>
                                            <textarea name="notes" rows="3" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Any specific requirements?"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                            <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm">
                                Confirm Switch
                            </button>
                            <button type="button" @click="showUpgradeModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Extension Modal -->
        <div x-show="showExtensionModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" @click="showExtensionModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('admin.subscription.extend') }}" method="POST">
                        @csrf
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900/30 sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="fa-solid fa-calendar-plus text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white" id="modal-title">
                                        Extend Subscription
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                            Extend your current plan validity.
                                        </p>
                                        
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Duration</label>
                                            <select name="extension_months" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                <option value="1">1 Month</option>
                                                <option value="3">3 Months</option>
                                                <option value="6">6 Months</option>
                                                <option value="12">12 Months (Best Value)</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Additional Notes (Optional)</label>
                                            <textarea name="notes" rows="3" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                            <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto sm:text-sm">
                                Confirm Extension
                            </button>
                            <button type="button" @click="showExtensionModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>