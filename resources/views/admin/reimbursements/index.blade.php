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
               class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition-colors flex items-center">
                <i class="fa-solid fa-file-excel mr-2"></i>Export to Excel
            </a>
        </div>
    </div>

    <!-- Bulk Actions Toolbar -->
    <div x-show="selected.length > 0" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6 flex items-center justify-between">
        <div class="flex items-center">
            <span class="text-blue-700 dark:text-blue-300 font-medium mr-4">
                <span x-text="selected.length"></span> items selected
            </span>
            <button @click="selected = []; allSelected = false" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 underline">
                Clear Selection
            </button>
        </div>
        <div class="flex space-x-2">
            <!-- Bulk Approve Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.away="open = false" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition-colors flex items-center">
                    <i class="fa-solid fa-check mr-2"></i> Approve Selected <i class="fa-solid fa-chevron-down ml-2 text-xs"></i>
                </button>
                <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 border border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ route('admin.reimbursements.bulk-approve') }}">
                        @csrf
                        <template x-for="id in selected" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <button type="submit" name="level" value="supervisor" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            As Supervisor
                        </button>
                        <button type="submit" name="level" value="manager" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            As Manager
                        </button>
                        <button type="submit" name="level" value="finance" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            As Finance
                        </button>
                    </form>
                </div>
            </div>

            <!-- Bulk Reject Button -->
            <button @click="$dispatch('open-bulk-reject-modal')" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors flex items-center">
                <i class="fa-solid fa-times mr-2"></i> Reject Selected
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <form method="GET" action="{{ route('admin.reimbursements.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select name="status" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
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
                <select name="category" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
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
                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors mr-2">
                    <i class="fa-solid fa-filter mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.reimbursements.index') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-white rounded-md transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                        {{ $reimbursements->where('status', 'pending')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-clock text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">In Progress</p>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        {{ $reimbursements->whereIn('status', ['approved_supervisor', 'approved_manager', 'verified_finance'])->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-spinner text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Paid</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                        {{ $reimbursements->where('status', 'paid')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Amount</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        Rp {{ number_format($reimbursements->sum('amount'), 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-money-bill-wave text-gray-600 dark:text-gray-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" @change="toggleAll" x-model="allSelected" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Reference
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Employee
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Category
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Amount
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($reimbursements as $reimbursement)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" value="{{ $reimbursement->id }}" x-model="selected" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    #{{ $reimbursement->reference_number }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $reimbursement->created_at->format('d M Y, H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                            <span class="text-blue-600 dark:text-blue-400 font-medium text-sm">
                                                {{ substr($reimbursement->employee->user->name, 0, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-3">
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
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
                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                        'approved_supervisor' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                        'approved_manager' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
                                        'verified_finance' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                                        'paid' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                        'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Pending',
                                        'approved_supervisor' => 'Supervisor Approved',
                                        'approved_manager' => 'Manager Approved',
                                        'verified_finance' => 'Finance Verified',
                                        'paid' => 'Paid',
                                        'rejected' => 'Rejected',
                                    ];
                                @endphp
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$reimbursement->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$reimbursement->status] ?? ucfirst(str_replace('_', ' ', $reimbursement->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.reimbursements.show', $reimbursement) }}" 
                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                    <i class="fa-solid fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-inbox text-gray-400 text-5xl mb-4"></i>
                                    <p class="text-gray-500 dark:text-gray-400 text-lg">No reimbursement requests found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($reimbursements->hasPages())
            <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $reimbursements->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Bulk Reject Modal -->
<div x-data="{ open: false }" 
     @open-bulk-reject-modal.window="open = true" 
     x-show="open" 
     class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
     style="display: none;">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4 shadow-xl" @click.away="open = false">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Reject Selected Reimbursements</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
            Are you sure you want to reject <span x-text="selected.length" class="font-bold"></span> selected items?
        </p>
        <form method="POST" action="{{ route('admin.reimbursements.bulk-reject') }}">
            @csrf
            <template x-for="id in selected" :key="id">
                <input type="hidden" name="ids[]" :value="id">
            </template>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rejection Reason</label>
                <textarea name="reason" rows="3" required
                          class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500"
                          placeholder="Please provide a reason for rejection..."></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" @click="open = false"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-white rounded-md transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors">
                    Reject Selected
                </button>
            </div>
        </form>
    </div>
</div>
</x-admin-layout>
