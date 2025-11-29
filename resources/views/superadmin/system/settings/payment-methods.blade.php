<x-superadmin-layout>
    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 md:mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">Payment Methods</h1>
                        <p class="mt-1 text-gray-600 dark:text-gray-400">Manage available payment methods for subscriptions</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('superadmin.subscriptions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                            <i class="fa-solid fa-arrow-left mr-2"></i>Back to Subscriptions
                        </a>
                        <a href="{{ route('superadmin.payment-methods.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fa-solid fa-plus mr-2"></i>Add Payment Method
                        </a>
                    </div>
                </div>
            </div>

            <!-- Payment Methods Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($paymentMethods as $paymentMethod)
                <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 transform hover:-translate-y-1">
                    <!-- Header with Gradient -->
                    <div class="px-6 py-5 bg-gradient-to-r {{ $paymentMethod->type === 'qris' ? 'from-purple-500 to-indigo-600' : ($paymentMethod->type === 'bank_transfer' ? 'from-blue-500 to-cyan-500' : 'from-emerald-500 to-teal-500') }}">
                        <div class="flex items-center justify-between text-white">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                    @if($paymentMethod->type === 'qris')
                                        <i class="fa-solid fa-qrcode text-xl"></i>
                                    @elseif($paymentMethod->type === 'bank_transfer')
                                        <i class="fa-solid fa-building-columns text-xl"></i>
                                    @else
                                        <i class="fa-solid fa-wallet text-xl"></i>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold">{{ $paymentMethod->name }}</h3>
                                    <p class="text-xs text-blue-100 uppercase tracking-wider font-medium">{{ str_replace('_', ' ', $paymentMethod->type) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $paymentMethod->is_active ? 'bg-white/20 text-white' : 'bg-red-500/80 text-white' }} backdrop-blur-sm">
                                    {{ $paymentMethod->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="px-6 py-6">
                        @if($paymentMethod->type === 'bank_transfer')
                            <div class="space-y-4">
                                @if($paymentMethod->bank_name)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Bank</span>
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $paymentMethod->bank_name }}</span>
                                    </div>
                                @endif
                                @if($paymentMethod->account_number)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Account No</span>
                                        <span class="text-sm font-mono font-bold text-gray-900 dark:text-white">{{ $paymentMethod->account_number }}</span>
                                    </div>
                                @endif
                                @if($paymentMethod->account_name)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Name</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $paymentMethod->account_name }}</span>
                                    </div>
                                @endif
                            </div>
                        @elseif($paymentMethod->type === 'qris')
                            <div class="flex flex-col items-center justify-center py-2">
                                @if($paymentMethod->qris_image)
                                    <div class="p-2 bg-white rounded-xl border border-gray-100 shadow-sm">
                                        <img src="{{ $paymentMethod->qris_image_url }}" alt="QRIS Code" class="w-32 h-32 object-contain">
                                    </div>
                                    <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">Scan to pay</p>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fa-solid fa-qrcode text-gray-300 text-4xl mb-2"></i>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">No QRIS image uploaded</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="space-y-4">
                                @if($paymentMethod->account_number)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Phone/ID</span>
                                        <span class="text-sm font-mono font-bold text-gray-900 dark:text-white">{{ $paymentMethod->account_number }}</span>
                                    </div>
                                @endif
                                @if($paymentMethod->account_name)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Name</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $paymentMethod->account_name }}</span>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if($paymentMethod->description)
                            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                                <p class="text-sm text-gray-600 dark:text-gray-400 italic">"{{ $paymentMethod->description }}"</p>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                        <form method="POST" action="{{ route('superadmin.payment-methods.toggle-status', $paymentMethod) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-sm {{ $paymentMethod->is_active ? 'text-orange-600 hover:text-orange-800' : 'text-green-600 hover:text-green-800' }} font-bold transition-colors">
                                {{ $paymentMethod->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        <div class="flex space-x-4">
                            <a href="{{ route('superadmin.payment-methods.edit', $paymentMethod) }}" class="text-gray-500 hover:text-blue-600 transition-colors" title="Edit">
                                <i class="fa-solid fa-pen-to-square text-lg"></i>
                            </a>
                            <form method="POST" action="{{ route('superadmin.payment-methods.destroy', $paymentMethod) }}"
                                  onsubmit="return confirm('Are you sure you want to delete this payment method?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-500 hover:text-red-600 transition-colors" title="Delete">
                                    <i class="fa-solid fa-trash-can text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full">
                    <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-700">
                        <div class="w-16 h-16 bg-blue-50 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-credit-card text-blue-500 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">No Payment Methods</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md mx-auto">Get started by adding your first payment method to allow customers to subscribe.</p>
                        <a href="{{ route('superadmin.payment-methods.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all shadow-lg hover:shadow-blue-500/30 font-medium">
                            <i class="fa-solid fa-plus mr-2"></i>Add Payment Method
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Information Card -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                <div class="flex items-start space-x-3">
                    <i class="fa-solid fa-info-circle text-blue-600 dark:text-blue-400 mt-1"></i>
                    <div>
                        <h3 class="text-lg font-medium text-blue-900 dark:text-blue-100 mb-2">Payment Methods Information</h3>
                        <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                            <li>• <strong>Value:</strong> The internal identifier used in the system (e.g., 'transfer', 'cash')</li>
                            <li>• <strong>Label:</strong> The display name shown to users (e.g., 'Transfer Bank', 'Tunai')</li>
                            <li>• <strong>Enabled:</strong> Check to make the payment method available for selection</li>
                            <li>• <strong>Order:</strong> Drag and drop to reorder payment methods in forms</li>
                        </ul>
                        <p class="mt-3 text-sm text-blue-700 dark:text-blue-300">
                            Changes to payment methods will be reflected in subscription creation and editing forms immediately.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>


        // Make payment method items sortable (drag and drop)
        document.addEventListener('DOMContentLoaded', function() {
            // This would require a sortable library like SortableJS
            // For now, we'll just make the items visually indicate they can be dragged
            const items = document.querySelectorAll('.payment-method-item');
            items.forEach(item => {
                item.style.cursor = 'move';
            });
        });
    </script>
</x-superadmin-layout>