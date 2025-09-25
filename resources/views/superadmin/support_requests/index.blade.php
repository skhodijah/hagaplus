<x-superadmin-layout>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header
                title="Support Requests"
                subtitle="Kelola permintaan dukungan dari instansi"
                :show-period-filter="false"
            />

            <x-section-card title="Daftar Permintaan">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Instansi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subject</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Priority</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($requests as $req)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $req->instansi->nama_instansi ?? '-' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $req->requester->email ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $req->subject }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($req->priority === 'urgent') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300
                                            @elseif($req->priority === 'high') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300
                                            @elseif($req->priority === 'normal') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300
                                            @else bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-300 @endif">
                                            {{ ucfirst($req->priority) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($req->status === 'open') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300
                                            @elseif($req->status === 'in_progress') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300
                                            @elseif($req->status === 'resolved') bg-violet-100 dark:bg-violet-900 text-violet-800 dark:text-violet-300
                                            @else bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-300 @endif">
                                            {{ ucwords(str_replace('_',' ', $req->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('superadmin.support_requests.show', $req->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada permintaan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $requests->links() }}</div>
            </x-section-card>
        </div>
    </div>
</x-superadmin-layout> 