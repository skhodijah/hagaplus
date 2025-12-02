<x-admin-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Hari Libur</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola daftar hari libur nasional dan cuti bersama.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.holidays.create') }}" class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all shadow-lg shadow-blue-200 dark:shadow-none">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Tambah Hari Libur
                    </a>
                </div>
            </div>

            <!-- Content -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Hari Libur</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tipe</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($holidays as $holiday)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        @if($holiday->start_date->equalTo($holiday->end_date))
                                            {{ $holiday->start_date->format('d M Y') }}
                                        @else
                                            {{ $holiday->start_date->format('d M') }} - {{ $holiday->end_date->format('d M Y') }}
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        @if($holiday->start_date->equalTo($holiday->end_date))
                                            {{ $holiday->start_date->format('l') }}
                                        @else
                                            {{ $holiday->start_date->diffInDays($holiday->end_date) + 1 }} Hari
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $holiday->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                        {{ $holiday->description ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($holiday->is_national_holiday)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                            Nasional
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                            Lainnya
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.holidays.edit', $holiday->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('admin.holidays.destroy', $holiday->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus hari libur ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                            <i class="fa-regular fa-calendar-xmark text-2xl"></i>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">Belum ada data hari libur</h3>
                                        <p class="text-sm max-w-sm mx-auto mb-6">Tambahkan hari libur nasional atau cuti bersama untuk mengatur jadwal kerja karyawan.</p>
                                        <a href="{{ route('admin.holidays.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors">
                                            <i class="fa-solid fa-plus mr-2"></i>
                                            Tambah Hari Libur
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($holidays->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $holidays->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
