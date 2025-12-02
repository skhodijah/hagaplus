<x-admin-layout>
<div class="container-fluid px-4 py-6" x-data="{ 
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
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Leave Requests</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage employee leave requests</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.leaves.create') }}" 
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 flex items-center shadow-sm hover:shadow">
                <i class="fa-solid fa-plus mr-2"></i>New Request
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <form method="GET" action="{{ route('admin.leaves.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved_supervisor" {{ request('status') == 'approved_supervisor' ? 'selected' : '' }}>Approved by User (Kepala Divisi)</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved by HRD</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Leave Type</label>
                <select name="leave_type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Types</option>
                    <option value="annual" {{ request('leave_type') == 'annual' ? 'selected' : '' }}>Annual Leave</option>
                    <option value="sick" {{ request('leave_type') == 'sick' ? 'selected' : '' }}>Sick Leave</option>
                    <option value="maternity" {{ request('leave_type') == 'maternity' ? 'selected' : '' }}>Maternity Leave</option>
                    <option value="emergency" {{ request('leave_type') == 'emergency' ? 'selected' : '' }}>Emergency Leave</option>
                    <option value="other" {{ request('leave_type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Employee name..." 
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 shadow-sm hover:shadow flex-1">
                    <i class="fa-solid fa-filter mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.leaves.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-all duration-200">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-xl shadow-sm border border-yellow-200 dark:border-yellow-800 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-yellow-700 dark:text-yellow-300 font-medium">Pending</p>
                    <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">
                        {{ $leaves->where('status', 'pending')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-yellow-200 dark:bg-yellow-700/30 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-clock text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl shadow-sm border border-blue-200 dark:border-blue-800 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-700 dark:text-blue-300 font-medium">In Progress</p>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-1">
                        {{ $leaves->whereIn('status', ['approved_supervisor'])->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-200 dark:bg-blue-700/30 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-spinner text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl shadow-sm border border-green-200 dark:border-green-800 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-green-700 dark:text-green-300 font-medium">Approved</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">
                        {{ $leaves->where('status', 'approved')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-200 dark:bg-green-700/30 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-xl shadow-sm border border-red-200 dark:border-red-800 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-red-700 dark:text-red-300 font-medium">Rejected</p>
                    <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-1">
                        {{ $leaves->where('status', 'rejected')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-200 dark:bg-red-600 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-times-circle text-red-600 dark:text-red-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" @change="toggleAll" x-model="allSelected" 
                                   class="w-4 h-4 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-2 focus:ring-blue-500 focus:ring-offset-0">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Employee
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Leave Type
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Duration
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Dates
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($leaves as $leave)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" value="{{ $leave->id }}" x-model="selected" 
                                       class="w-4 h-4 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-2 focus:ring-blue-500 focus:ring-offset-0">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm">
                                        <span class="text-white font-semibold text-sm">
                                            {{ substr($leave->user->name, 0, 2) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $leave->user->name }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $leave->user->employee->department->name ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900 dark:text-white">{{ ucfirst($leave->leave_type) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $leave->days_count }} Days</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                {{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-800',
                                        'approved_supervisor' => 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800',
                                        'approved' => 'bg-green-100 text-green-700 border-green-200 dark:bg-green-900/30 dark:text-green-300 dark:border-green-800',
                                        'rejected' => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Pending',
                                        'approved_supervisor' => 'User (Kepala Divisi) ✓',
                                        'approved' => 'HRD ✓',
                                        'rejected' => 'Rejected',
                                    ];
                                @endphp
                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full border {{ $statusClasses[$leave->status] ?? 'bg-gray-100 text-gray-700 border-gray-200' }}">
                                    {{ $statusLabels[$leave->status] ?? ucfirst($leave->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.leaves.show', $leave) }}" 
                                   class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                    <span class="font-medium">View</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                        <i class="fa-solid fa-calendar-xmark text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">No leave requests found</p>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Try adjusting your filters</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($leaves->hasPages())
            <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $leaves->links() }}
            </div>
        @endif
    </div>
</div>
</x-admin-layout>