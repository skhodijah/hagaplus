<x-admin-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Manage Permissions</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Configure access permissions for: <span class="font-semibold">{{ $role->name }}</span></p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('admin.roles.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm transition-all duration-200">
                        <i class="fa-solid fa-arrow-left mr-2"></i>Back to Roles
                    </a>
                </div>
            </div>

            <!-- Role Info Card -->
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-lg p-6 text-white mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold">{{ $role->name }}</h3>
                        <p class="text-blue-100 text-sm mt-1">{{ $role->description }}</p>
                    </div>
                    @if($role->is_default)
                        <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm border border-white/10 text-xs font-medium">
                            Default Role
                        </span>
                    @endif
                </div>
            </div>

            <!-- Permissions Form -->
            <form method="POST" action="{{ route('admin.roles.permissions.update', $role) }}">
                @csrf
                @method('PUT')

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="border-b border-gray-100 dark:border-gray-700 px-6 py-4 bg-gray-50/50 dark:bg-gray-800">
                        <h3 class="font-bold text-gray-900 dark:text-white">Available Permissions</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Select the permissions this role should have</p>
                    </div>

                    <div class="p-6 space-y-8">
                        @foreach($permissions as $group => $groupPermissions)
                            <div class="border-l-4 border-blue-500 pl-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-lg font-bold text-gray-900 dark:text-white capitalize">
                                        <i class="fa-solid fa-{{ $group === 'employees' ? 'users' : ($group === 'attendance' ? 'calendar-check' : ($group === 'leaves' ? 'umbrella-beach' : ($group === 'payroll' ? 'money-bill-wave' : ($group === 'organization' ? 'sitemap' : ($group === 'policies' ? 'file-contract' : ($group === 'reports' ? 'chart-bar' : 'cog')))))) }} mr-2 text-blue-500"></i>
                                        {{ ucfirst($group) }}
                                    </h4>
                                    <button type="button" 
                                            onclick="toggleGroup('{{ $group }}')"
                                            class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 font-medium">
                                        Select All
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($groupPermissions as $permission)
                                        <label class="flex items-start p-4 rounded-xl bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors cursor-pointer group-{{ $group }}">
                                            <input type="checkbox" 
                                                   name="permissions[]" 
                                                   value="{{ $permission->id }}"
                                                   {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                                                   class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 permission-{{ $group }}">
                                            <div class="ml-3">
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $permission->name }}</span>
                                                @if($permission->description)
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $permission->description }}</p>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-100 dark:border-gray-700 px-6 py-4 bg-gray-50/50 dark:bg-gray-800 flex justify-end space-x-3">
                        <a href="{{ route('admin.roles.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 shadow-sm transition-all duration-200">
                            <i class="fa-solid fa-save mr-2"></i>Save Permissions
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleGroup(group) {
            const checkboxes = document.querySelectorAll('.permission-' + group);
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
        }
    </script>
    @endpush
</x-admin-layout>
