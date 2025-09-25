<x-superadmin-layout>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header
                title="Detail Subscription"
                subtitle="Informasi lengkap subscription"
                :show-period-filter="false"
            />
            
            <div class="flex justify-end mb-6">
                <a href="{{ route('superadmin.subscriptions.index') }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                    <i class="fa-solid fa-arrow-left mr-2"></i>Kembali
                </a>
                <a href="{{ route('superadmin.subscriptions.edit', $subscription) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                    <i class="fa-solid fa-edit mr-2"></i>Edit
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <x-section-card title="Informasi Subscription">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Instansi</dt>
                                <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $subscription->instansi->nama_instansi ?? '-' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Paket</dt>
                                <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $subscription->package->name ?? '-' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                <dd class="mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($subscription->current_status == 'active') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300
                                        @elseif($subscription->current_status == 'inactive') bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-300
                                        @elseif($subscription->current_status == 'expired') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300
                                        @elseif($subscription->current_status == 'suspended') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300
                                        @else bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-300 @endif">
                                        {{ ucfirst($subscription->current_status) }}
                                    </span>
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Mulai</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ optional($subscription->start_date)->format('d M Y') }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Berakhir</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ optional($subscription->end_date)->format('d M Y') }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Harga</dt>
                                <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">Rp {{ number_format($subscription->price, 0, ',', '.') }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $subscription->created_at->format('d M Y H:i') }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Update</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $subscription->updated_at->format('d M Y H:i') }}</dd>
                            </div>
                        </dl>
                    </x-section-card>
                </div>

                <div>
                    <x-section-card title="Aksi">
                        <div class="space-y-3">
                            @if($subscription->canBeExtended() || $subscription->current_status === 'expired')
                                <form method="POST" action="{{ route('superadmin.subscriptions.extend', $subscription) }}" onsubmit="return confirm('Apakah Anda yakin ingin memperpanjang subscription 1 bulan?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                        <i class="fa-solid fa-calendar-plus mr-2"></i>Perpanjang 1 Bulan
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('superadmin.subscriptions.edit', $subscription) }}" class="w-full inline-flex justify-center items-center bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                <i class="fa-solid fa-edit mr-2"></i>Edit Subscription
                            </a>

                            <form method="POST" action="{{ route('superadmin.subscriptions.destroy', $subscription) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus subscription ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <i class="fa-solid fa-trash mr-2"></i>Hapus Subscription
                                </button>
                            </form>
                        </div>
                    </x-section-card>

                    @if($subscription->package)
                    @php
                        // Handle both: relation collection or JSON array cast
                        $pkg = $subscription->package;
                        $featuresProp = $pkg->features ?? null;
                        $packageFeaturesCount = 0;
                        if ($featuresProp instanceof \Illuminate\Support\Collection) {
                            $packageFeaturesCount = $featuresProp->count();
                        } elseif (is_array($featuresProp)) {
                            $packageFeaturesCount = count($featuresProp);
                        } elseif (method_exists($pkg, 'features')) {
                            try { $packageFeaturesCount = $pkg->features()->count(); } catch (\Throwable $e) { $packageFeaturesCount = 0; }
                        }
                    @endphp
                    <x-section-card title="Informasi Paket">
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Fitur:</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $packageFeaturesCount }} fitur</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Max Employee:</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $subscription->package->max_employees ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Max Branch:</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $subscription->package->max_branches ?? '-' }}</span>
                            </div>
                        </div>
                    </x-section-card>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-superadmin-layout>
