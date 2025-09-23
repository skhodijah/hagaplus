<x-superadmin-layout>
    <div class="bg-white rounded-lg shadow p-4">
        <h1 class="text-lg font-semibold mb-4">Daftar Subscriptions</h1>

        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">Instansi</th>
                    <th class="px-4 py-2 text-left">Package</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Start Date</th>
                    <th class="px-4 py-2 text-left">End Date</th>
                    <th class="px-4 py-2 text-left">Payment</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscriptions as $subscription)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $subscription->instansi->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $subscription->package->name ?? '-' }}</td>
                        <td class="px-4 py-2">
                            @if($subscription->status === 'active')
                                <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-600 rounded-full">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-600 rounded-full">Expired</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ $subscription->start_date->format('d M Y') }}</td>
                        <td class="px-4 py-2">{{ $subscription->end_date->format('d M Y') }}</td>
                        <td class="px-4 py-2">{{ $subscription->payment ?? '-' }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('superadmin.subscriptions.show', $subscription->id) }}" class="text-blue-600 hover:underline">View</a>
                            <form action="{{ route('superadmin.subscriptions.destroy', $subscription->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin mau hapus?')" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-2 text-center text-gray-500">Belum ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-superadmin-layout>
