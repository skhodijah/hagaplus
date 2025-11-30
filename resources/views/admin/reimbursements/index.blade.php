<x-admin-layout>
<div class="container-fluid px-4 py-6" x-data="{ 
    selected: [], 
    allSelected: false,
    toggleAll() {
        this.allSelected = !this.allSelected;
        this.selected = this.allSelected ? [{{ $reimbursements->pluck('id')->implode(',') }}] : [];
    }
}">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Reimbursement Requests</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage employee reimbursement requests</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.reimbursements.export', request()->all()) }}" 
               class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-all duration-200 flex items-center shadow-sm hover:shadow">
                <i class="fa-solid fa-file-excel mr-2"></i>Export to Excel
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <form method="GET" action="{{ route('admin.reimbursements.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved_supervisor" {{ request('status') == 'approved_supervisor' ? 'selected' : '' }}>Approved by Supervisor</option>
                    <option value="approved_manager" {{ request('status') == 'approved_manager' ? 'selected' : '' }}>Approved by Manager</option>
                    <option value="verified_finance" {{ request('status') == 'verified_finance' ? 'selected' : '' }}>Verified by Finance</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                <select name="category" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Categories</option>
                    <option value="Transport" {{ request('category') == 'Transport' ? 'selected' : '' }}>Transport</option>
                    <option value="Meal" {{ request('category') == 'Meal' ? 'selected' : '' }}>Meal</option>
                    <option value="Accommodation" {{ request('category') == 'Accommodation' ? 'selected' : '' }}>Accommodation</option>
                    <option value="Medical" {{ request('category') == 'Medical' ? 'selected' : '' }}>Medical</option>
                    <option value="Office Supplies" {{ request('category') == 'Office Supplies' ? 'selected' : '' }}>Office Supplies</option>
                    <option value="Other" {{ request('category') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Reference or employee name..." 
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 shadow-sm hover:shadow flex-1">
                    <i class="fa-solid fa-filter mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.reimbursements.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-all duration-200">
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
                        {{ $reimbursements->where('status', 'pending')->count() }}
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
                        {{ $reimbursements->whereIn('status', ['approved_supervisor', 'approved_manager', 'verified_finance'])->count() }}
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
                    <p class="text-sm text-green-700 dark:text-green-300 font-medium">Paid</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">
                        {{ $reimbursements->where('status', 'paid')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-200 dark:bg-green-700/30 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 rounded-xl shadow-sm border border-gray-200 dark:border-gray-600 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-700 dark:text-gray-300 font-medium">Total Amount</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        Rp {{ number_format($reimbursements->sum('amount'), 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-gray-200 dark:bg-gray-600 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-money-bill-wave text-gray-600 dark:text-gray-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Toolbar (Above Table) -->
    <div x-show="selected.length > 0" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 mb-4 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-check text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-blue-900 dark:text-blue-100">
                        <span x-text="selected.length"></span> item<span x-show="selected.length > 1">s</span> selected
                    </p>
                    <button @click="selected = []; allSelected = false" class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 underline">
                        Clear selection
                    </button>
                </div>
            </div>
        </div>
        <div class="flex gap-2">
            <!-- Bulk Approve Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.away="open = false" 
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-all duration-200 flex items-center gap-2 shadow-sm hover:shadow">
                    <i class="fa-solid fa-check text-sm"></i>
                    <span class="font-medium">Approve</span>
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </button>
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-1 z-50 border border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ route('admin.reimbursements.bulk-approve') }}">
                        @csrf
                        <template x-for="id in selected" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <button type="submit" name="level" value="supervisor" 
                                class="block w-full text-left px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors">
                            <i class="fa-solid fa-user-tie w-5"></i> As Supervisor
                        </button>
                        <button type="submit" name="level" value="manager" 
                                class="block w-full text-left px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors">
                            <i class="fa-solid fa-user-shield w-5"></i> As Manager
                        </button>
                        <button type="submit" name="level" value="finance" 
                                class="block w-full text-left px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors">
                            <i class="fa-solid fa-money-check-dollar w-5"></i> As Finance
                        </button>
                    </form>
                </div>
            </div>

            <!-- Bulk Reject Button -->
            <button @click="$dispatch('open-bulk-reject-modal')" 
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-all duration-200 flex items-center gap-2 shadow-sm hover:shadow">
                <i class="fa-solid fa-times text-sm"></i>
                <span class="font-medium">Reject</span>
            </button>
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
                            Reference
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Employee
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Category
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Amount
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
                    @forelse($reimbursements as $reimbursement)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" value="{{ $reimbursement->id }}" x-model="selected" 
                                       class="w-4 h-4 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-2 focus:ring-blue-500 focus:ring-offset-0">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                    #{{ $reimbursement->reference_number }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $reimbursement->created_at->format('d M Y, H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm">
                                        <span class="text-white font-semibold text-sm">
                                            {{ substr($reimbursement->employee->user->name, 0, 2) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $reimbursement->employee->user->name }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $reimbursement->employee->department->name ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900 dark:text-white">{{ $reimbursement->category }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                {{ $reimbursement->date_of_expense->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $reimbursement->currency }} {{ number_format($reimbursement->amount, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-800',
                                        'approved_supervisor' => 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800',
                                        'approved_manager' => 'bg-indigo-100 text-indigo-700 border-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-300 dark:border-indigo-800',
                                        'verified_finance' => 'bg-purple-100 text-purple-700 border-purple-200 dark:bg-purple-900/30 dark:text-purple-300 dark:border-purple-800',
                                        'paid' => 'bg-green-100 text-green-700 border-green-200 dark:bg-green-900/30 dark:text-green-300 dark:border-green-800',
                                        'rejected' => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Pending',
                                        'approved_supervisor' => 'Supervisor ✓',
                                        'approved_manager' => 'Manager ✓',
                                        'verified_finance' => 'Finance ✓',
                                        'paid' => 'Paid',
                                        'rejected' => 'Rejected',
                                    ];
                                @endphp
                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full border {{ $statusClasses[$reimbursement->status] ?? 'bg-gray-100 text-gray-700 border-gray-200' }}">
                                    {{ $statusLabels[$reimbursement->status] ?? ucfirst(str_replace('_', ' ', $reimbursement->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.reimbursements.show', $reimbursement) }}" 
                                   class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                    <span class="font-medium">View</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                        <i class="fa-solid fa-inbox text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">No reimbursement requests found</p>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Try adjusting your filters</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($reimbursements->hasPages())
            <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $reimbursements->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Bulk Reject Modal -->
<div x-data="{ open: false }" 
     @open-bulk-reject-modal.window="open = true" 
     x-show="open" 
     class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center"
     style="display: none;">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-md mx-4 shadow-2xl" @click.away="open = false">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-times text-red-600 dark:text-red-400 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Reject Reimbursements</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    <span x-text="selected.length"></span> item<span x-show="selected.length > 1">s</span> selected
                </p>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.reimbursements.bulk-reject') }}">
            @csrf
            <template x-for="id in selected" :key="id">
                <input type="hidden" name="ids[]" :value="id">
            </template>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rejection Reason *</label>
                <textarea name="reason" rows="3" required
                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent"
                          placeholder="Please provide a reason for rejection..."></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" @click="open = false"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-all duration-200 font-medium">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-all duration-200 font-medium shadow-sm hover:shadow">
                    <i class="fa-solid fa-times mr-2"></i>Reject Selected
                </button>
            </div>
        </form>
    </div>
</div>
</x-admin-layout>
