<x-superadmin-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Subscription Detail</h2>

                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Instansi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $subscription->instansi->name ?? '-' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Package</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $subscription->package->name ?? '-' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($subscription->status) }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Payment Status</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($subscription->payment_status) ?? '-' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $subscription->start_date?->format('d M Y') ?? '-' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">End Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $subscription->end_date?->format('d M Y') ?? '-' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Price</dt>
                            <dd class="mt-1 text-sm text-gray-900">Rp {{ number_format($subscription->price, 0, ',', '.') }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $subscription->payment_method ?? '-' }}</dd>
                        </div>

                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Notes</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $subscription->notes ?? '-' }}</dd>
                        </div>
                    </dl>

                    <div class="mt-6 flex items-center justify-end space-x-3">
                        <a href="{{ route('superadmin.subscriptions.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Back
                        </a>
                        <form method="POST" action="{{ route('superadmin.subscriptions.destroy', $subscription->id) }}" 
                              onsubmit="return confirm('Are you sure you want to delete this subscription?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-superadmin-layout>
