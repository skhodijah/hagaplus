<x-superadmin-layout>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header
                title="Instansi Management"
                subtitle="Kelola semua instansi dalam sistem HagaPlus"
                :show-period-filter="false"
            />

            @if(session('success'))
                <div class="mb-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <x-section-card title="Daftar Instansi" class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Total: {{ $instansis->total() }} instansi
                    </div>
                    <a href="{{ route('superadmin.instansi.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        <i class="fa-solid fa-plus mr-2"></i>Tambah Instansi
                    </a>
                </div>

                @if($instansis->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Instansi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subdomain</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dibuat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($instansis as $instansi)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $instansi->nama_instansi }}</div>
                                            @if($instansi->package)
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Paket: {{ $instansi->package->name }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">{{ $instansi->subdomain }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $instansi->subdomain }}.hagaplus.com</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">{{ $instansi->email ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if($instansi->is_active) bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 @else bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300 @endif">
                                                    {{ $instansi->is_active ? 'Instansi Aktif' : 'Instansi Nonaktif' }}
                                                </span>
                                                @php
                                                    $latestSubscription = $instansi->subscriptions->first();
                                                    $currentStatus = $latestSubscription?->current_status ?? null;
                                                @endphp
                                                @if($currentStatus)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                        @if($currentStatus == 'active') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300
                                                        @elseif($currentStatus == 'inactive') bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-300
                                                        @elseif($currentStatus == 'expired') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300
                                                        @elseif($currentStatus == 'suspended') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300
                                                        @else bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-300 @endif">
                                                        {{ 'Subscr: ' . ucfirst($currentStatus) }}
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-300">Belum berlangganan</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $instansi->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('superadmin.instansi.show', $instansi) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 transition-colors duration-200">
                                                    <i class="fa-solid fa-eye mr-1"></i>Lihat
                                                </a>
                                                <a href="{{ route('superadmin.instansi.edit', $instansi) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 transition-colors duration-200">
                                                    <i class="fa-solid fa-edit mr-1"></i>Edit
                                                </a>
                                                <form method="POST" action="{{ route('superadmin.instansi.destroy', $instansi) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus instansi ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition-colors duration-200">
                                                        <i class="fa-solid fa-trash mr-1"></i>Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $instansis->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600">
                            <i class="fa-solid fa-building text-4xl"></i>
                        </div>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada instansi</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan membuat instansi baru.</p>
                        <div class="mt-6">
                            <a href="{{ route('superadmin.instansi.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                                <i class="fa-solid fa-plus mr-2"></i>Tambah Instansi Baru
                            </a>
                        </div>
                    </div>
                @endif
            </x-section-card>
        </div>
    </div>
</x-superadmin-layout>
