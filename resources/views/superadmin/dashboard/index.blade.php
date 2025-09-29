<x-superadmin-layout :notifications="$notifications" :unreadCount="$unreadCount">
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Super Admin</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Ringkasan dan analisis kinerja sistem HagaPlus
                    </p>
                </div>
                <div class="mt-4 md:mt-0">
                    <select 
                        id="period-filter" 
                        class="text-sm border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        onchange="window.location.href = '?period=' + this.value"
                    >
                        <option value="7d" {{ request('period') === '7d' ? 'selected' : '' }}>7 Hari Terakhir</option>
                        <option value="30d" {{ request('period') === '30d' || !request('period') ? 'selected' : '' }}>30 Hari Terakhir</option>
                        <option value="90d" {{ request('period') === '90d' ? 'selected' : '' }}>90 Hari Terakhir</option>
                        <option value="1y" {{ request('period') === '1y' ? 'selected' : '' }}>1 Tahun Terakhir</option>
                    </select>
                </div>
            </div>

            <!-- Overview Cards -->
            @include('superadmin.dashboard.partials.overview')

            <!-- Quick Actions -->
            @include('superadmin.dashboard.partials.quick-actions')

            <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Charts Section -->
                <div class="lg:col-span-2">
                    @include('superadmin.dashboard.partials.charts')
                </div>
                
                <!-- Recent Activities -->
                <div class="space-y-6">
                    <!-- Recent Instansi -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                Instansi Terbaru
                                <a href="{{ route('superadmin.instansi.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline float-right">Lihat Semua</a>
                            </h3>
                            <div class="space-y-4">
                                @forelse($recentInstansi as $instansi)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                            <i class="fas fa-building text-blue-600 dark:text-blue-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $instansi->nama_instansi }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                Dibuat {{ $instansi->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada instansi terbaru</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Recent Subscriptions -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                Langganan Terbaru
                                <a href="{{ route('superadmin.subscriptions.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline float-right">Lihat Semua</a>
                            </h3>
                            <div class="space-y-4">
                                @forelse($recentSubscriptions as $subscription)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                                            <i class="fas fa-crown text-green-600 dark:text-green-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $subscription->instansi->nama_instansi ?? 'Instansi Tidak Ditemukan' }}
                                                <span class="text-xs px-2 py-0.5 rounded-full {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                                    {{ ucfirst($subscription->status) }}
                                                </span>
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $subscription->package->nama_paket ?? 'Paket Tidak Ditemukan' }} • 
                                                {{ $subscription->start_date->format('d M Y') }} - {{ $subscription->end_date->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada langganan terbaru</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Handle period filter change
        document.getElementById('period-filter')?.addEventListener('change', function() {
            const period = this.value;
            const url = new URL(window.location.href);
            url.searchParams.set('period', period);
            window.location.href = url.toString();
        });
    </script>
    @endpush
</x-superadmin-layout>
