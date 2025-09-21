<x-superadmin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $instansi->nama_instansi }}</h1>
                    <p class="mt-2 text-gray-600">Instansi Details</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('superadmin.instansi.edit', $instansi) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Edit
                    </a>
                    <a href="{{ route('superadmin.instansi.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Back to List
                    </a>
                </div>
            </div>

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Instansi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $instansi->nama_instansi }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Subdomain</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $instansi->subdomain }}.hagaplus.com</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status Langganan</dt>
                            <dd class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($instansi->status_langganan == 'active') bg-green-100 text-green-800
                                    @elseif($instansi->status_langganan == 'inactive') bg-gray-100 text-gray-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($instansi->status_langganan) }}
                                </span>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created At</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $instansi->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Updated At</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $instansi->updated_at->format('M d, Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Subscriptions for this Instansi -->
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Subscriptions</h3>
                <div class="bg-white shadow overflow-hidden sm:rounded-md">
                    <div class="px-4 py-5 sm:p-6">
                        @if($instansi->subscriptions->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($instansi->subscriptions as $subscription)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $subscription->package->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                        @if($subscription->status == 'active') bg-green-100 text-green-800
                                                        @elseif($subscription->status == 'inactive') bg-gray-100 text-gray-800
                                                        @else bg-red-100 text-red-800 @endif">
                                                        {{ ucfirst($subscription->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $subscription->start_date->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $subscription->end_date->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    Rp {{ number_format($subscription->price) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No subscriptions found for this instansi.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-superadmin-layout>
