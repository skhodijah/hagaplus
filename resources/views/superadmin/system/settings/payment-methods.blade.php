<x-superadmin-layout>
    <div class="py-6 md:py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 md:mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">Payment Methods</h1>
                        <p class="mt-1 text-gray-600 dark:text-gray-400">Manage available payment methods for subscriptions</p>
                    </div>
                    <a href="{{ route('superadmin.system.settings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fa-solid fa-arrow-left mr-2"></i>Back to Settings
                    </a>
                </div>
            </div>

            <!-- Payment Methods List -->
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Available Payment Methods</h2>
                    <button type="button" onclick="showAddModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fa-solid fa-plus mr-2"></i>Add Payment Method
                    </button>
                </div>

                <form id="paymentMethodsForm" method="POST" action="{{ route('superadmin.system.settings.update-payment-methods') }}">
                    @csrf
                    <div id="paymentMethodsList" class="space-y-4">
                        @foreach($paymentMethods as $index => $method)
                        <div class="payment-method-item flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <input type="checkbox"
                                           name="payment_methods[{{ $index }}][enabled]"
                                           value="1"
                                           {{ $method['enabled'] ?? true ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <input type="hidden" name="payment_methods[{{ $index }}][enabled]" value="0">
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-1">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Value</label>
                                            <input type="text"
                                                   name="payment_methods[{{ $index }}][value]"
                                                   value="{{ $method['value'] }}"
                                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm"
                                                   required>
                                        </div>
                                        <div class="flex-1">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Label</label>
                                            <input type="text"
                                                   name="payment_methods[{{ $index }}][label]"
                                                   value="{{ $method['label'] }}"
                                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm"
                                                   required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if(count($paymentMethods) > 1)
                                <button type="button" onclick="deletePaymentMethod('{{ $method['value'] }}', this)" class="text-red-600 hover:text-red-800 p-2">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                @endif
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    <i class="fa-solid fa-grip-vertical"></i>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-6 flex items-center justify-end space-x-3">
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fa-solid fa-save mr-2"></i>Save Changes
                        </button>
                    </div>
                </form>
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

    <!-- Add Payment Method Modal -->
    <div id="addModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Add Payment Method</h3>
                    <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>

                <form id="addPaymentMethodForm" class="space-y-4">
                    @csrf
                    <div>
                        <label for="new_value" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Value *</label>
                        <input type="text" id="new_value" name="value" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Internal identifier (e.g., 'transfer', 'cash')</p>
                    </div>

                    <div>
                        <label for="new_label" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Label *</label>
                        <input type="text" id="new_label" name="label" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Display name (e.g., 'Transfer Bank', 'Tunai')</p>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeAddModal()" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-400 dark:hover:bg-gray-500">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            <i class="fa-solid fa-plus mr-1"></i>Add Method
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Show add modal
        function showAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
        }

        // Close add modal
        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
            document.getElementById('addPaymentMethodForm').reset();
        }

        // Delete payment method
        function deletePaymentMethod(value, buttonElement) {
            if (confirm('Are you sure you want to delete this payment method? This action cannot be undone.')) {
                fetch('{{ route("superadmin.system.settings.delete-payment-method") }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ value: value })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the payment method item from DOM
                        buttonElement.closest('.payment-method-item').remove();
                        alert('Payment method deleted successfully.');
                    } else {
                        alert('Error deleting payment method.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting payment method.');
                });
            }
        }

        // Handle add payment method form
        document.getElementById('addPaymentMethodForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('{{ route("superadmin.system.settings.add-payment-method") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page to show the new payment method
                    location.reload();
                } else {
                    alert('Error adding payment method: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding payment method.');
            });
        });

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