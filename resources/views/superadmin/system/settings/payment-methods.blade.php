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
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                @if($paymentMethod->type === 'qris')
                                    <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                        <i class="fa-solid fa-qrcode text-purple-600 dark:text-purple-400"></i>
                                    </div>
                                @elseif($paymentMethod->type === 'bank_transfer')
                                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                        <i class="fa-solid fa-building-columns text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                @else
                                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                        <i class="fa-solid fa-mobile-screen-button text-green-600 dark:text-green-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $paymentMethod->name }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 capitalize">{{ str_replace('_', ' ', $paymentMethod->type) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $paymentMethod->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }}">
                                    {{ $paymentMethod->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="px-6 py-4">
                        @if($paymentMethod->type === 'bank_transfer')
                            <div class="space-y-2">
                                @if($paymentMethod->account_name)
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Account Name</label>
                                        <p class="text-sm text-gray-900 dark:text-white">{{ $paymentMethod->account_name }}</p>
                                    </div>
                                @endif
                                @if($paymentMethod->account_number)
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Account Number</label>
                                        <p class="text-sm text-gray-900 dark:text-white font-mono">{{ $paymentMethod->account_number }}</p>
                                    </div>
                                @endif
                                @if($paymentMethod->bank_name)
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Bank</label>
                                        <p class="text-sm text-gray-900 dark:text-white">{{ $paymentMethod->bank_name }}</p>
                                    </div>
                                @endif
                            </div>
                        @elseif($paymentMethod->type === 'qris')
                            <div class="space-y-2">
                                @if($paymentMethod->qris_image)
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">QRIS Code</label>
                                        <div class="mt-2">
                                            <img src="{{ $paymentMethod->qris_image_url }}" alt="QRIS Code" class="w-24 h-24 object-contain border border-gray-200 dark:border-gray-600 rounded">
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fa-solid fa-qrcode text-gray-400 text-2xl mb-2"></i>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">No QRIS image uploaded</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="space-y-2">
                                @if($paymentMethod->account_number)
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Phone/Account</label>
                                        <p class="text-sm text-gray-900 dark:text-white">{{ $paymentMethod->account_number }}</p>
                                    </div>
                                @endif
                                @if($paymentMethod->account_name)
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Account Name</label>
                                        <p class="text-sm text-gray-900 dark:text-white">{{ $paymentMethod->account_name }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if($paymentMethod->description)
                            <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $paymentMethod->description }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="px-6 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                        <div class="flex items-center justify-between">
                            <form method="POST" action="{{ route('superadmin.payment-methods.toggle-status', $paymentMethod) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-sm {{ $paymentMethod->is_active ? 'text-orange-600 hover:text-orange-800' : 'text-green-600 hover:text-green-800' }} font-medium">
                                    {{ $paymentMethod->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                            <div class="flex space-x-3">
                                <a href="{{ route('superadmin.payment-methods.edit', $paymentMethod) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('superadmin.payment-methods.destroy', $paymentMethod) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this payment method?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <i class="fa-solid fa-credit-card text-gray-400 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Payment Methods</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">Get started by adding your first payment method.</p>
                        <a href="{{ route('superadmin.payment-methods.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
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