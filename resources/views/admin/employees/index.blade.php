<x-admin-layout>
<div class="container-fluid px-4 py-6" x-data="{ 
    selected: [], 
    allSelected: false,
    showDeleteModal: false,
    deleteForm: null,
    employeeName: '',
    toggleAll() {
        this.allSelected = !this.allSelected;
        if (this.allSelected) {
            this.selected = Array.from(document.querySelectorAll('input[type=checkbox][x-model=selected]')).map(el => el.value);
        } else {
            this.selected = [];
        }
    },
    openDeleteModal(form, name) {
        this.deleteForm = form;
        this.employeeName = name;
        this.showDeleteModal = true;
    },
    confirmDelete() {
        if (this.deleteForm) {
            this.deleteForm.submit();
        }
    }
}">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Employee Management</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage and monitor employee information and status</p>
        </div>
        
        @hasPermission('create-employees')
        @if($canCreateEmployee)
        <div class="flex space-x-2">
            <a href="{{ route('admin.employees.create') }}" 
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 flex items-center shadow-sm hover:shadow">
                <i class="fa-solid fa-plus mr-2"></i>New Employee
            </a>
        </div>
        @endif
        @endhasPermission
    </div>

    <!-- Setup Checklist (Only if needed) -->
    @hasPermission('create-employees')
    @if(!$canCreateEmployee)
        <div class="mb-6 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/40 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-list-check text-2xl text-amber-600 dark:text-amber-400"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-amber-900 dark:text-amber-100 mb-2">
                        Setup Required Before Adding Employees
                    </h3>
                    <p class="text-sm text-amber-700 dark:text-amber-300 mb-4">
                        Please complete the following setup steps before you can add employees:
                    </p>
                    <div class="space-y-3">
                        <!-- Attendance Policy -->
                        <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg border {{ $setupChecks['attendance_policy'] ? 'border-green-200 dark:border-green-800' : 'border-amber-200 dark:border-amber-800' }}">
                            <div class="flex items-center gap-3">
                                @if($setupChecks['attendance_policy'])
                                    <i class="fa-solid fa-check-circle text-green-600 dark:text-green-400 text-lg"></i>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Attendance Policy Configured</span>
                                @else
                                    <i class="fa-solid fa-circle-exclamation text-amber-600 dark:text-amber-400 text-lg"></i>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Configure Attendance Policy</span>
                                @endif
                            </div>
                            @if(!$setupChecks['attendance_policy'])
                                <a href="{{ route('admin.attendance-policy.index') }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                                    <i class="fa-solid fa-arrow-right mr-2"></i>
                                    Setup Now
                                </a>
                            @endif
                        </div>

                        <!-- Divisions -->
                        <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg border {{ $setupChecks['has_divisions'] ? 'border-green-200 dark:border-green-800' : 'border-amber-200 dark:border-amber-800' }}">
                            <div class="flex items-center gap-3">
                                @if($setupChecks['has_divisions'])
                                    <i class="fa-solid fa-check-circle text-green-600 dark:text-green-400 text-lg"></i>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Divisions Created</span>
                                @else
                                    <i class="fa-solid fa-circle-exclamation text-amber-600 dark:text-amber-400 text-lg"></i>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Create at least one Division</span>
                                @endif
                            </div>
                            @if(!$setupChecks['has_divisions'])
                                <a href="{{ route('admin.divisions.index') }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                                    <i class="fa-solid fa-arrow-right mr-2"></i>
                                    Setup Now
                                </a>
                            @endif
                        </div>

                        <!-- Departments & Positions (Hierarchy) -->
                        <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg border {{ ($setupChecks['has_departments'] && $setupChecks['has_positions']) ? 'border-green-200 dark:border-green-800' : 'border-amber-200 dark:border-amber-800' }}">
                            <div class="flex items-center gap-3">
                                @if($setupChecks['has_departments'] && $setupChecks['has_positions'])
                                    <i class="fa-solid fa-check-circle text-green-600 dark:text-green-400 text-lg"></i>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Departments & Positions Created</span>
                                @else
                                    <i class="fa-solid fa-circle-exclamation text-amber-600 dark:text-amber-400 text-lg"></i>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Create Departments & Positions</span>
                                @endif
                            </div>
                            @if(!$setupChecks['has_departments'] || !$setupChecks['has_positions'])
                                <a href="{{ route('admin.hierarchy.index') }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                                    <i class="fa-solid fa-arrow-right mr-2"></i>
                                    Setup Now
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @endhasPermission

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <form method="GET" action="{{ route('admin.employees.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="terminated" {{ request('status') === 'terminated' ? 'selected' : '' }}>Terminated</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role</label>
                <select name="role_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Department</label>
                <select name="department_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, ID, Position..." 
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 shadow-sm hover:shadow flex-1">
                    <i class="fa-solid fa-filter mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.employees.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-all duration-200">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl shadow-sm border border-blue-200 dark:border-blue-800 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-700 dark:text-blue-300 font-medium">Total Employees</p>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-1">
                        {{ $employees->total() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-200 dark:bg-blue-700/30 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-users text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl shadow-sm border border-green-200 dark:border-green-800 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-green-700 dark:text-green-300 font-medium">Active</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">
                        {{ $employees->where('status', 'active')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-200 dark:bg-green-700/30 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-user-check text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-xl shadow-sm border border-yellow-200 dark:border-yellow-800 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-yellow-700 dark:text-yellow-300 font-medium">Inactive</p>
                    <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">
                        {{ $employees->where('status', 'inactive')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-yellow-200 dark:bg-yellow-700/30 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-user-clock text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-xl shadow-sm border border-red-200 dark:border-red-800 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-red-700 dark:text-red-300 font-medium">Terminated</p>
                    <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-1">
                        {{ $employees->where('status', 'terminated')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-200 dark:bg-red-600 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-user-xmark text-red-600 dark:text-red-400 text-xl"></i>
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
                            Position
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Role
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Department
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
                    @forelse($employees as $employee)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" value="{{ $employee->id }}" x-model="selected" 
                                       class="w-4 h-4 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-2 focus:ring-blue-500 focus:ring-offset-0">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center shadow-sm">
                                        <span class="text-white font-semibold text-sm">
                                            {{ substr($employee->user->name, 0, 2) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $employee->user->name }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $employee->user->email }}
                                        </div>
                                        <div class="text-xs text-gray-400 dark:text-gray-500">
                                            ID: {{ $employee->employee_id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900 dark:text-white">{{ $employee->position ? $employee->position->name : 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $employee->instansiRole ? $employee->instansiRole->name : 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $employee->department ? $employee->department->name : 'N/A' }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $employee->branch ? $employee->branch->name : 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClasses = [
                                        'active' => 'bg-green-100 text-green-700 border-green-200 dark:bg-green-900/30 dark:text-green-300 dark:border-green-800',
                                        'inactive' => 'bg-yellow-100 text-yellow-700 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-800',
                                        'terminated' => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800',
                                    ];
                                @endphp
                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full border {{ $statusClasses[$employee->status] ?? 'bg-gray-100 text-gray-700 border-gray-200' }}">
                                    {{ ucfirst($employee->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.employees.show', $employee) }}" 
                                       class="p-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                       title="View Details">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    
                                    @hasPermission('edit-employees')
                                    <a href="{{ route('admin.employees.edit', $employee) }}" 
                                       class="p-2 text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded-lg transition-colors"
                                       title="Edit Employee">
                                        <i class="fa-solid fa-edit"></i>
                                    </a>
                                    @endhasPermission

                                    @hasPermission('delete-employees')
                                    <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                @click.stop="openDeleteModal($el.closest('form'), '{{ $employee->user->name }}')"
                                                class="p-2 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                                title="Delete Employee">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                    @endhasPermission
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                        <i class="fa-solid fa-users-slash text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">No employees found</p>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Try adjusting your filters</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($employees->hasPages())
            <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $employees->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true"
         @keydown.escape.window="showDeleteModal = false">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
             x-show="showDeleteModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="showDeleteModal = false"></div>

        <!-- Modal panel -->
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="showDeleteModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fa-solid fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-white" id="modal-title">
                                Delete Employee
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Are you sure you want to delete <strong x-text="employeeName" class="text-gray-900 dark:text-white"></strong>? 
                                    This action cannot be undone and will permanently remove all employee data.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                    <button type="button" 
                            @click="confirmDelete(); showDeleteModal = false"
                            class="inline-flex w-full justify-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:w-auto transition-colors duration-200">
                        <i class="fa-solid fa-trash mr-2"></i>
                        Delete
                    </button>
                    <button type="button" 
                            @click="showDeleteModal = false"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-700 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 sm:mt-0 sm:w-auto transition-colors duration-200">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</x-admin-layout>