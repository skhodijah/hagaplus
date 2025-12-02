<x-admin-layout>
    <div class="space-y-6" x-data="{ 
        selected: [], 
        allSelected: false,
        toggleAll() {
            this.allSelected = !this.allSelected;
            if (this.allSelected) {
                this.selected = Array.from(document.querySelectorAll('input[type=checkbox][x-model=selected]')).map(el => el.value);
            } else {
                this.selected = [];
            }
        }
    }">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Attendance Revisions</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage employee attendance correction requests</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-2xl shadow-sm border border-yellow-200 dark:border-yellow-800 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-yellow-700 dark:text-yellow-300 font-medium">Pending</p>
                        <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">
                            {{ $revisions->where('status', 'pending')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-200 dark:bg-yellow-700/30 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-clock text-yellow-600 dark:text-yellow-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-2xl shadow-sm border border-blue-200 dark:border-blue-800 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-700 dark:text-blue-300 font-medium">In Progress</p>
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-1">
                            {{ $revisions->where('status', 'approved_supervisor')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-200 dark:bg-blue-700/30 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-spinner text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-2xl shadow-sm border border-green-200 dark:border-green-800 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-green-700 dark:text-green-300 font-medium">Approved</p>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">
                            {{ $revisions->where('status', 'approved')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-200 dark:bg-green-700/30 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-2xl shadow-sm border border-red-200 dark:border-red-800 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-red-700 dark:text-red-300 font-medium">Rejected</p>
                        <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-1">
                            {{ $revisions->where('status', 'rejected')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-red-200 dark:bg-red-600 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-times-circle text-red-600 dark:text-red-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <form method="GET" action="{{ route('admin.attendance.revisions.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Status</label>
                    <select name="status" class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved_supervisor" {{ request('status') == 'approved_supervisor' ? 'selected' : '' }}>Approved by Supervisor</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved by HRD</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Search</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search employee name..." 
                               class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white pl-10 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-all shadow-lg shadow-indigo-200 dark:shadow-none flex-1">
                        <i class="fa-solid fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.attendance.revisions.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition-all">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 dark:bg-gray-700/50 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-4 font-medium">Employee</th>
                            <th class="px-6 py-4 font-medium">Type</th>
                            <th class="px-6 py-4 font-medium">Original Time</th>
                            <th class="px-6 py-4 font-medium">Revised Time</th>
                            <th class="px-6 py-4 font-medium">Reason</th>
                            <th class="px-6 py-4 font-medium">Status</th>
                            <th class="px-6 py-4 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($revisions as $revision)
                            <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold">
                                            {{ substr($revision->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $revision->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $revision->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $revision->revision_type === 'check_in' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400' }}">
                                        {{ $revision->revision_type === 'check_in' ? 'Check In' : 'Check Out' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                    {{ $revision->original_time ? $revision->original_time->format('d M Y H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white">
                                    {{ $revision->revised_time->format('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-w-xs truncate" title="{{ $revision->reason }}">
                                        {{ $revision->reason }}
                                    </div>
                                    @if($revision->proof_photo)
                                        <a href="{{ asset('storage/' . $revision->proof_photo) }}" data-fancybox="proof" class="text-xs text-indigo-600 hover:text-indigo-800 mt-1 inline-block">
                                            <i class="fa-solid fa-image mr-1"></i> View Proof
                                        </a>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                            'approved_supervisor' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                            'approved' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                            'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Pending',
                                            'approved_supervisor' => 'Supervisor Approved',
                                            'approved' => 'HRD Approved',
                                            'rejected' => 'Rejected',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$revision->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$revision->status] ?? ucfirst($revision->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        @if($revision->status === 'pending')
                                            <button onclick="openApproveModal('{{ $revision->id }}', 'supervisor')" class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded-lg text-xs transition-colors shadow-sm">
                                                Approve (SPV)
                                            </button>
                                        @endif
                                        
                                        @if($revision->status === 'approved_supervisor' || ($revision->status === 'pending' && auth()->user()->system_role_id <= 2))
                                            <button onclick="openApproveModal('{{ $revision->id }}', 'hrd')" class="text-white bg-green-600 hover:bg-green-700 px-3 py-1.5 rounded-lg text-xs transition-colors shadow-sm">
                                                Approve (HRD)
                                            </button>
                                        @endif

                                        @if($revision->status !== 'rejected' && $revision->status !== 'approved')
                                            <button onclick="openRejectModal('{{ $revision->id }}')" class="text-white bg-red-600 hover:bg-red-700 px-3 py-1.5 rounded-lg text-xs transition-colors shadow-sm">
                                                Reject
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fa-solid fa-clipboard-check text-2xl text-gray-400 dark:text-gray-500"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">No Revisions Found</h3>
                                    <p class="text-gray-500 dark:text-gray-400">Try adjusting your filters or search query.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($revisions->hasPages())
                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $revisions->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Approve Modal -->
    <div id="approve-modal" class="hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="relative p-4 w-full max-w-md">
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">
                <div class="p-6 text-center">
                    <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center mx-auto mb-4 text-green-600 dark:text-green-400">
                        <i class="fa-solid fa-check text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Approve Request</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Are you sure you want to approve this revision request?</p>
                    
                    <form id="approve-form" method="POST" class="text-left">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="level" id="approve-level">
                        
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Note (Optional)</label>
                            <textarea name="notes" rows="3" class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Add a note..."></textarea>
                        </div>

                        <div class="flex gap-3">
                            <button type="button" onclick="closeApproveModal()" class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl transition-colors shadow-lg shadow-green-200 dark:shadow-none">
                                Approve
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="reject-modal" class="hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="relative p-4 w-full max-w-md">
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">
                <div class="p-6 text-center">
                    <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center mx-auto mb-4 text-red-600 dark:text-red-400">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Reject Request</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Please provide a reason for rejecting this request.</p>
                    
                    <form id="reject-form" method="POST" class="text-left">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Rejection Reason</label>
                            <textarea name="notes" rows="3" class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Enter reason..." required></textarea>
                        </div>

                        <div class="flex gap-3">
                            <button type="button" onclick="closeRejectModal()" class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-colors shadow-lg shadow-red-200 dark:shadow-none">
                                Reject Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openApproveModal(id, level) {
            const form = document.getElementById('approve-form');
            form.action = `/admin/attendance/revisions/${id}/approve`;
            document.getElementById('approve-level').value = level;
            document.getElementById('approve-modal').classList.remove('hidden');
        }

        function closeApproveModal() {
            document.getElementById('approve-modal').classList.add('hidden');
        }

        function openRejectModal(id) {
            const form = document.getElementById('reject-form');
            form.action = `/admin/attendance/revisions/${id}/reject`;
            document.getElementById('reject-modal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('reject-modal').classList.add('hidden');
        }
    </script>
    @endpush
</x-admin-layout>
