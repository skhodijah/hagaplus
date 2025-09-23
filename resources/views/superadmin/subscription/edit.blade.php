<x-superadmin-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-6 py-6">
                    <h2 class="text-2xl font-bold mb-4">Edit Subscription</h2>
                    <form method="POST" action="{{ route('superadmin.subscriptions.update', $subscription->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Instansi</label>
                            <input type="text" value="{{ $subscription->instansi->name ?? '-' }}" disabled class="mt-1 block w-full border-gray-300 rounded-md bg-gray-100">
                        </div>

                        <div class="mb-4">
                            <label for="package_id" class="block text-sm font-medium text-gray-700">Package</label>
                            <select name="package_id" id="package_id" class="mt-1 block w-full border-gray-300 rounded-md">
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" {{ $subscription->package_id == $package->id ? 'selected' : '' }}>
                                        {{ $package->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input type="date" name="start_date" id="start_date" value="{{ $subscription->start_date->format('Y-m-d') }}" class="mt-1 block w-full border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                <input type="date" name="end_date" id="end_date" value="{{ $subscription->end_date->format('Y-m-d') }}" class="mt-1 block w-full border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="price" class="block text-sm font-medium text-gray-700">Price (Rp)</label>
                            <input type="number" name="price" id="price" value="{{ $subscription->price }}" class="mt-1 block w-full border-gray-300 rounded-md">
                        </div>

                        <div class="mt-4">
                            <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment Status</label>
                            <select name="payment_status" id="payment_status" class="mt-1 block w-full border-gray-300 rounded-md">
                                <option value="pending" {{ $subscription->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $subscription->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ $subscription->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Subscription Status</label>
                            <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md">
                                <option value="active" {{ $subscription->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="expired" {{ $subscription->status == 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="cancelled" {{ $subscription->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md">{{ $subscription->notes }}</textarea>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('superadmin.subscriptions.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-superadmin-layout>
