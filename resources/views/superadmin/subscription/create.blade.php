<x-superadmin-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-6 py-6">
                    <h2 class="text-2xl font-bold mb-4">Create Subscription</h2>
                    <form method="POST" action="{{ route('superadmin.subscriptions.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="instansi_id" class="block text-sm font-medium text-gray-700">Instansi</label>
                            <select name="instansi_id" id="instansi_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @foreach($instansis as $instansi)
                                    <option value="{{ $instansi->id }}">{{ $instansi->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="package_id" class="block text-sm font-medium text-gray-700">Package</label>
                            <select name="package_id" id="package_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}">{{ $package->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="mt-1 block w-full border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="mt-1 block w-full border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="price" class="block text-sm font-medium text-gray-700">Price (Rp)</label>
                            <input type="number" name="price" id="price" class="mt-1 block w-full border-gray-300 rounded-md">
                        </div>

                        <div class="mt-4">
                            <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment Status</label>
                            <select name="payment_status" id="payment_status" class="mt-1 block w-full border-gray-300 rounded-md">
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Subscription Status</label>
                            <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md">
                                <option value="active">Active</option>
                                <option value="expired">Expired</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md"></textarea>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('superadmin.subscriptions.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-superadmin-layout>
