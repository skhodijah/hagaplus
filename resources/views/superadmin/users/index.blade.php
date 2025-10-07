<x-superadmin-layout>
    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">User Management</h1>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Manage all users across the system</p>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            <i class="fa-solid fa-users text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Users</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $users->total() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                            <i class="fa-solid fa-user-check text-green-600 dark:text-green-400"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Verified Users</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ \App\Models\Core\User::whereNotNull('email_verified_at')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                            <i class="fa-solid fa-crown text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Admins</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ \App\Models\Core\User::where('role', 'admin')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-orange-100 dark:bg-orange-900 rounded-lg">
                            <i class="fa-solid fa-user-tie text-orange-600 dark:text-orange-400"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Employees</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ \App\Models\Core\User::where('role', 'employee')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-between">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('superadmin.users.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            <i class="fa-solid fa-plus mr-2"></i>Add New User
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6 mb-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, or phone..." class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role</label>
                        <select name="role" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Roles</option>
                            <option value="superadmin" {{ request('role') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="employee" {{ request('role') == 'employee' ? 'selected' : '' }}>Employee</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Instansi</label>
                        <select name="instansi_id" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Instansi</option>
                            @foreach($instansis as $instansi)
                                <option value="{{ $instansi->id }}" {{ request('instansi_id') == $instansi->id ? 'selected' : '' }}>{{ $instansi->nama_instansi }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fa-solid fa-search"></i>
                        </button>
                        <a href="{{ route('superadmin.users.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            <i class="fa-solid fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Bulk Actions -->
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 mb-6">
                <form id="bulkActionForm" method="POST" action="{{ route('superadmin.users.bulk-action') }}">
                    @csrf
                    <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
                        <div class="flex items-center gap-4">
                            <label class="flex items-center">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Select All</span>
                            </label>
                            <select name="action" id="bulkAction" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" disabled>
                                <option value="">Choose Action</option>
                                <option value="delete">Delete</option>
                            </select>
                            <button type="submit" id="bulkSubmit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                Apply
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Users Table -->
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left">
                                    <input type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Instansi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Verified</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Created</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" form="bulkActionForm" class="user-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($user->avatar)
                                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ substr($user->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @switch($user->role)
                                                @case('superadmin') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                @case('admin') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                                @case('employee') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @default bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                            @endswitch">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $user->instansi->nama_instansi ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($user->email_verified_at) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @endif">
                                            {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $user->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('superadmin.users.show', $user) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('superadmin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                <i class="fa-solid fa-edit"></i>
                                            </a>
                                            @if($user->role !== 'superadmin')
                                                <form method="POST" action="{{ route('superadmin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const userCheckboxes = document.querySelectorAll('.user-checkbox');
            const bulkAction = document.getElementById('bulkAction');
            const bulkSubmit = document.getElementById('bulkSubmit');

            // Handle select all functionality
            selectAllCheckbox.addEventListener('change', function() {
                userCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkActions();
            });

            // Handle individual checkbox changes
            userCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
                    selectAllCheckbox.checked = checkedBoxes.length === userCheckboxes.length;
                    selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < userCheckboxes.length;
                    updateBulkActions();
                });
            });

            function updateBulkActions() {
                const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
                const hasSelection = checkedBoxes.length > 0;

                bulkAction.disabled = !hasSelection;
                bulkSubmit.disabled = !hasSelection;
            }

            // Handle bulk action form submission
            document.getElementById('bulkActionForm').addEventListener('submit', function(e) {
                if (!bulkAction.value) {
                    e.preventDefault();
                    alert('Please select an action.');
                    return;
                }

                const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
                if (checkedBoxes.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one user.');
                    return;
                }

                if (!confirm(`Are you sure you want to ${bulkAction.value} ${checkedBoxes.length} user(s)?`)) {
                    e.preventDefault();
                }
            });
        });
    </script>
</x-superadmin-layout>