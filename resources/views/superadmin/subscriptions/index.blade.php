<x-superadmin-layout>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header
                title="Subscription Management"
                subtitle="Kelola semua subscription instansi"
                :show-period-filter="false"
            />

            <div class="flex justify-end mb-6">
                <a href="{{ route('superadmin.subscriptions.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                    <i class="fa-solid fa-plus mr-2"></i>Buat Subscription Baru
                </a>
            </div>

            <x-section-card title="Daftar Subscriptions">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Instansi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Package</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal Mulai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal Berakhir</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($subscriptions as $subscription)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $subscription->instansi->nama_instansi ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $subscription->package->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($subscription->status == 'active') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300
                                            @elseif($subscription->status == 'inactive') bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-300
                                            @elseif($subscription->status == 'expired') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300
                                            @elseif($subscription->status == 'cancelled') bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-300
                                            @elseif($subscription->status == 'pending_verification') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300
                                            @else bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-300 @endif">
                                            {{ ucfirst($subscription->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $subscription->start_date->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $subscription->end_date->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-medium">
                                        Rp {{ number_format($subscription->price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <a href="{{ route('superadmin.subscriptions.show', $subscription) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Lihat</a>
                                        <a href="{{ route('superadmin.subscriptions.edit', $subscription) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Edit</a>
                                        @if($subscription->canBeExtended())
                                            <form method="POST" action="{{ route('superadmin.subscriptions.extend', $subscription) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin memperpanjang subscription 1 bulan?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">Perpanjang</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center">
                                        <div class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600">
                                            <i class="fa-solid fa-receipt text-4xl"></i>
                                        </div>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada subscription</h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Belum ada subscription yang terdaftar.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-section-card>
        </div>
    </div>
</x-superadmin-layout>
