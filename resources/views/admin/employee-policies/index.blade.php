<x-admin-layout>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header
                title="Employee Policies"
                subtitle="Manage individual attendance policies for employees"
                :show-period-filter="false"
            />

            <!-- Action Buttons -->
            <div class="mb-6 flex justify-between items-center">
                <div class="flex space-x-3">
                    <a href="{{ route('admin.employee-policies.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fa-solid fa-plus mr-2"></i>Create Policy
                    </a>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6 mb-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Employee</label>
                        <select name="employee_id" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Employees</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                        <div class="flex space-x-2">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search policies or employees..." class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <i class="fa-solid fa-search"></i>
                            </button>
                            <a href="{{ route('admin.employee-policies.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                <i class="fa-solid fa-times"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Policies Table -->
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Employee Policies</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Policy Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Work Schedule</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Effective Period</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($policies as $policy)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($policy->employee->avatar)
                                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $policy->employee->avatar) }}" alt="">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $policy->employee->initials() }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $policy->employee->name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $policy->employee->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $policy->name }}</div>
                                        @if($policy->description)
                                            <div class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-xs">{{ $policy->description }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        <div>{{ $policy->formatted_schedule }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $policy->formatted_work_days }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($policy->is_active && $policy->isCurrentlyEffective()) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($policy->is_active) bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                            @if($policy->is_active && $policy->isCurrentlyEffective())
                                                Active
                                            @elseif($policy->is_active)
                                                Scheduled
                                            @else
                                                Inactive
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        @if($policy->effective_from)
                                            <div>From: {{ $policy->effective_from->format('M d, Y') }}</div>
                                        @endif
                                        @if($policy->effective_until)
                                            <div>Until: {{ $policy->effective_until->format('M d, Y') }}</div>
                                        @else
                                            <div class="text-green-600 dark:text-green-400">Ongoing</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.employee-policies.show', $policy) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.employee-policies.edit', $policy) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                <i class="fa-solid fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.employee-policies.toggle-status', $policy) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300" title="{{ $policy->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="fa-solid fa-{{ $policy->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.employee-policies.destroy', $policy) }}" class="inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this policy?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fa-solid fa-file-contract text-gray-400 text-4xl mb-4"></i>
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Employee Policies</h3>
                                            <p class="text-gray-500 dark:text-gray-400 mb-4">Get started by creating your first employee policy.</p>
                                            <a href="{{ route('admin.employee-policies.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                                <i class="fa-solid fa-plus mr-2"></i>Create Policy
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($policies->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $policies->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>