<x-superadmin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Super Admin Dashboard</h1>
                <p class="mt-2 text-gray-600">Overview of your system</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-blue-50 p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-blue-800">Total Instansi</h3>
                            <p class="text-3xl font-bold text-blue-600">{{ $totalInstansi }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-green-800">Active Subscriptions</h3>
                            <p class="text-3xl font-bold text-green-600">{{ $activeSubscriptions }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-purple-50 p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-purple-800">Total Packages</h3>
                            <p class="text-3xl font-bold text-purple-600">{{ $totalPackages }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-orange-50 p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-orange-800">Total Subscriptions</h3>
                            <p class="text-3xl font-bold text-orange-600">{{ $totalSubscriptions }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Data Tables -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Instansi -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Instansi</h3>
                    </div>
                    <div class="p-6">
                        @if($recentInstansi->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentInstansi as $instansi)
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $instansi->nama_instansi }}</p>
                                            <p class="text-xs text-gray-500">{{ $instansi->subdomain }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            @if($instansi->status_langganan == 'active') bg-green-100 text-green-800
                                            @elseif($instansi->status_langganan == 'inactive') bg-gray-100 text-gray-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($instansi->status_langganan) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No instansi found</p>
                        @endif
                    </div>
                </div>

                <!-- Recent Packages -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Packages</h3>
                    </div>
                    <div class="p-6">
                        @if($recentPackages->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentPackages as $package)
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $package->name }}</p>
                                            <p class="text-xs text-gray-500">Rp {{ number_format($package->price) }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            @if($package->is_active) bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $package->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No packages found</p>
                        @endif
                    </div>
                </div>

                <!-- Recent Subscriptions -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Subscriptions</h3>
                    </div>
                    <div class="p-6">
                        @if($recentSubscriptions->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentSubscriptions as $subscription)
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $subscription->instansi->nama_instansi ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500">{{ $subscription->package->name ?? 'N/A' }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            @if($subscription->status == 'active') bg-green-100 text-green-800
                                            @elseif($subscription->status == 'inactive') bg-gray-100 text-gray-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($subscription->status) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No subscriptions found</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('superadmin.instansi.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded text-center">
                        Add New Instansi
                    </a>
                    <a href="{{ route('superadmin.packages.create') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded text-center">
                        Add New Package
                    </a>
                    <a href="{{ route('superadmin.subscriptions.index') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-4 rounded text-center">
                        View All Subscriptions
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-superadmin-layout>
