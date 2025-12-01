<x-admin-layout>
    <x-page-header title="{{ __('Organization Management') }}" class="mb-6">
        <!-- Actions based on active tab (handled via Alpine x-show if needed, or just inline in tabs) -->
    </x-page-header>

    <div x-data="{ 
        activeTab: '{{ $activeTab }}',
        roleModalOpen: false,
        divisionModalOpen: false,
        changeTab(tab) {
            this.activeTab = tab;
            // Update URL without reloading
            const url = new URL(window.location);
            url.searchParams.set('tab', tab);
            window.history.pushState({}, '', url);
        }
    }">
        <!-- Tabs Navigation -->
        <div class="mb-6">
            <div class="sm:hidden">
                <label for="tabs" class="sr-only">Select a tab</label>
                <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
                <select id="tabs" name="tabs" class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" @change="changeTab($event.target.value)">
                    <option value="roles" :selected="activeTab === 'roles'">Roles & Permissions</option>
                    <option value="divisions" :selected="activeTab === 'divisions'">Divisions</option>
                    <option value="hierarchy" :selected="activeTab === 'hierarchy'">Hierarchy Structure</option>
                </select>
            </div>
            <div class="hidden sm:block">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button @click="changeTab('roles')"
                            :class="activeTab === 'roles' 
                                ? 'border-blue-500 text-blue-600 dark:text-blue-400' 
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                            class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200">
                            <span :class="activeTab === 'roles' 
                                ? 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300' 
                                : 'bg-gray-100 text-gray-500 group-hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:group-hover:bg-gray-700'"
                                class="mr-3 py-0.5 px-2.5 rounded-full text-xs font-medium transition-colors duration-200">
                                <i class="fas fa-user-shield"></i>
                            </span>
                            <span>Roles & Permissions</span>
                        </button>

                        <button @click="changeTab('divisions')"
                            :class="activeTab === 'divisions' 
                                ? 'border-blue-500 text-blue-600 dark:text-blue-400' 
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                            class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200">
                            <span :class="activeTab === 'divisions' 
                                ? 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300' 
                                : 'bg-gray-100 text-gray-500 group-hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:group-hover:bg-gray-700'"
                                class="mr-3 py-0.5 px-2.5 rounded-full text-xs font-medium transition-colors duration-200">
                                <i class="fas fa-building"></i>
                            </span>
                            <span>Divisions</span>
                        </button>

                        <button @click="changeTab('hierarchy')"
                            :class="activeTab === 'hierarchy' 
                                ? 'border-blue-500 text-blue-600 dark:text-blue-400' 
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                            class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200">
                            <span :class="activeTab === 'hierarchy' 
                                ? 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300' 
                                : 'bg-gray-100 text-gray-500 group-hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:group-hover:bg-gray-700'"
                                class="mr-3 py-0.5 px-2.5 rounded-full text-xs font-medium transition-colors duration-200">
                                <i class="fas fa-sitemap"></i>
                            </span>
                            <span>Hierarchy Structure</span>
                        </button>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Roles Tab -->
        <div x-show="activeTab === 'roles'" x-cloak>
            <div class="mb-4 flex justify-end">
                <button @click="roleModalOpen = true" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-sm transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>Add New Role
                </button>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <!-- Search and Filter -->
                    <form method="GET" action="{{ route('admin.organization.index') }}" class="mb-6">
                        <input type="hidden" name="tab" value="roles">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="role_search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                                <input type="text" name="role_search" id="role_search" value="{{ request('role_search') }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                    placeholder="Search by name...">
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2 transition-colors duration-200">
                                    <i class="fas fa-search mr-2"></i>Search
                                </button>
                                <a href="{{ route('admin.organization.index', ['tab' => 'roles']) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                                    <i class="fas fa-redo mr-2"></i>Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Roles Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">System Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Employees</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($roles as $role)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $role->name }}</div>
                                                @if($role->is_default)
                                                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        Default
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $role->systemRole->slug === 'admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                                {{ $role->systemRole->name }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $role->description ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">{{ $role->employees->count() }} employees</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($role->is_active)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    Active
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.roles.permissions.edit', $role) }}" class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300 mr-3" title="Manage Permissions">
                                                <i class="fas fa-key"></i>
                                            </a>
                                            @if(!$role->is_default)
                                                <a href="{{ route('admin.roles.edit', $role) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this role?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            No roles found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $roles->appends(['tab' => 'roles'])->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Divisions Tab -->
        <div x-show="activeTab === 'divisions'" x-cloak>
            <div class="mb-4 flex justify-end">
                <button @click="divisionModalOpen = true" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-sm transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>Add New Division
                </button>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <!-- Search & Filter -->
                    <form method="GET" action="{{ route('admin.organization.index') }}" class="mb-6">
                        <input type="hidden" name="tab" value="divisions">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="division_search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                                <input type="text" name="division_search" id="division_search" value="{{ request('division_search') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="Search by name or code...">
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2 transition-colors duration-200">
                                    <i class="fas fa-search mr-2"></i>Search
                                </button>
                                <a href="{{ route('admin.organization.index', ['tab' => 'divisions']) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                                    <i class="fas fa-redo mr-2"></i>Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Divisions Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Code</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Employees</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($divisions as $division)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $division->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">{{ $division->code }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">{{ $division->employees->count() }} employees</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($division->is_active)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    Active
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.divisions.edit', $division) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.divisions.destroy', $division) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this division?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            No divisions found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $divisions->appends(['tab' => 'divisions'])->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Hierarchy Tab -->
        <div x-show="activeTab === 'hierarchy'" x-cloak>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6"
                 x-data="{ 
                    deptModalOpen: false, 
                    posModalOpen: false,
                    editDeptModalOpen: false,
                    editPosModalOpen: false,
                    currentDivisionId: null,
                    currentDepartmentId: null,
                    currentPositionId: null,
                    deptName: '',
                    deptDesc: '',
                    posName: '',
                    posDesc: '',
                    posRoleId: '',
                    editDeptName: '',
                    editDeptDesc: '',
                    editDeptAction: '',
                    editPosName: '',
                    editPosDesc: '',
                    editPosRoleId: '',
                    editPosAction: '',
                    
                    openAddDept(divId) {
                        this.currentDivisionId = divId;
                        this.deptName = '';
                        this.deptDesc = '';
                        this.deptModalOpen = true;
                    },
                    
                    openAddPos(deptId) {
                        this.currentDepartmentId = deptId;
                        this.posName = '';
                        this.posDesc = '';
                        this.posRoleId = '';
                        this.posModalOpen = true;
                    },

                    openEditDept(dept) {
                        this.currentDepartmentId = dept.id;
                        this.editDeptName = dept.name;
                        this.editDeptDesc = dept.description;
                        this.editDeptAction = '/admin/departments/' + dept.id;
                        this.editDeptModalOpen = true;
                    },

                    openEditPos(pos) {
                        this.currentPositionId = pos.id;
                        this.editPosName = pos.name;
                        this.editPosDesc = pos.description;
                        this.editPosRoleId = pos.instansi_role_id;
                        this.editPosAction = '/admin/positions/' + pos.id;
                        this.editPosModalOpen = true;
                    }
                 }">

                <div class="mb-6 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Organization Structure</h3>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <span class="inline-flex items-center mr-3"><span class="w-3 h-3 bg-blue-100 border border-blue-300 rounded-sm mr-1"></span> Division</span>
                        <span class="inline-flex items-center mr-3"><span class="w-3 h-3 bg-green-100 border border-green-300 rounded-sm mr-1"></span> Department</span>
                        <span class="inline-flex items-center"><span class="w-3 h-3 bg-purple-100 border border-purple-300 rounded-sm mr-1"></span> Position</span>
                    </div>
                </div>

                <div class="space-y-8">
                    @forelse ($hierarchyDivisions as $division)
                        <div class="relative pl-4 border-l-2 border-blue-200 dark:border-blue-900">
                            <!-- Division Node -->
                            <div class="flex items-center mb-4">
                                <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-blue-500 border-2 border-white dark:border-gray-800"></div>
                                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3 shadow-sm w-full md:w-auto md:min-w-[300px] flex justify-between items-center group">
                                    <div class="flex items-center">
                                        <div class="p-2 bg-blue-100 dark:bg-blue-800 rounded-lg mr-3">
                                            <i class="fas fa-building text-blue-600 dark:text-blue-300"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900 dark:text-white">{{ $division->name }}</h4>
                                            @if($division->code)
                                                <span class="text-xs text-blue-600 dark:text-blue-400 font-mono">{{ $division->code }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <button @click="openAddDept({{ $division->id }})" class="opacity-0 group-hover:opacity-100 transition-opacity p-1 text-blue-600 hover:bg-blue-100 rounded" title="Add Department">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Departments -->
                            <div class="ml-8 space-y-6">
                                @forelse ($division->departments as $department)
                                    <div class="relative pl-6 border-l-2 border-green-200 dark:border-green-900 pb-2">
                                        <!-- Department Node -->
                                        <div class="flex items-center mb-3">
                                            <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-green-500 border-2 border-white dark:border-gray-800"></div>
                                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-2 shadow-sm w-full md:w-auto md:min-w-[280px] flex justify-between items-center group">
                                                <div class="flex items-center">
                                                    <div class="p-1.5 bg-green-100 dark:bg-green-800 rounded-md mr-3">
                                                        <i class="fas fa-sitemap text-green-600 dark:text-green-300 text-sm"></i>
                                                    </div>
                                                    <span class="font-semibold text-gray-800 dark:text-gray-200 text-sm">{{ $department->name }}</span>
                                                </div>
                                                <div class="flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <button @click="openAddPos({{ $department->id }})" class="p-1 text-green-600 hover:bg-green-100 rounded" title="Add Position">
                                                        <i class="fas fa-plus text-xs"></i>
                                                    </button>
                                                    <button @click="openEditDept({{ $department }})" class="p-1 text-blue-600 hover:bg-blue-100 rounded" title="Edit">
                                                        <i class="fas fa-edit text-xs"></i>
                                                    </button>
                                                    <form action="{{ route('admin.departments.destroy', $department->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this department?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-1 text-red-600 hover:bg-red-100 rounded" title="Delete">
                                                            <i class="fas fa-trash text-xs"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Positions -->
                                        <div class="ml-8 grid gap-2">
                                            @forelse ($department->positions as $position)
                                                <div class="relative flex items-center">
                                                    <div class="absolute -left-4 w-4 h-0.5 bg-purple-200 dark:bg-purple-900"></div>
                                                    <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded p-2 shadow-sm flex-grow flex justify-between items-center group hover:border-purple-300 dark:hover:border-purple-700 transition-colors">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-user-tie text-purple-500 mr-2 text-xs"></i>
                                                            <div>
                                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $position->name }}</div>
                                                                @if ($position->instansiRole)
                                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $position->instansiRole->name }}</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                            <button @click="openEditPos({{ $position }})" class="p-1 text-blue-600 hover:bg-blue-50 rounded">
                                                                <i class="fas fa-edit text-xs"></i>
                                                            </button>
                                                            <form action="{{ route('admin.positions.destroy', $position->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this position?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="p-1 text-red-600 hover:bg-red-50 rounded">
                                                                    <i class="fas fa-trash text-xs"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="text-xs text-gray-400 italic ml-2">No positions</div>
                                            @endforelse
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-sm text-gray-400 italic ml-8">No departments</div>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i class="fas fa-sitemap text-gray-300 text-6xl mb-4"></i>
                            <p class="text-gray-500">No organizational structure found.</p>
                            <button @click="changeTab('divisions')" class="text-blue-600 hover:underline mt-2">Create a Division to start</button>
                        </div>
                    @endforelse
                </div>

                <!-- Modals (Copied and adapted from original file) -->
                <!-- Add Department Modal -->
                <div x-show="deptModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <form action="{{ route('admin.departments.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="division_id" :value="currentDivisionId">
                                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Add New Department</h3>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="deptName">
                                            Department Name
                                        </label>
                                        <input x-model="deptName" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="deptName" type="text" placeholder="e.g. IT Support" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="deptDesc">
                                            Description (Optional)
                                        </label>
                                        <textarea x-model="deptDesc" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="deptDesc" placeholder="Description..."></textarea>
                                    </div>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                        Create
                                    </button>
                                    <button @click="deptModalOpen = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Add Position Modal -->
                <div x-show="posModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <form action="{{ route('admin.positions.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="department_id" :value="currentDepartmentId">
                                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Add New Position</h3>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="posName">
                                            Position Name
                                        </label>
                                        <input x-model="posName" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="posName" type="text" placeholder="e.g. Senior Developer" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="posRoleId">
                                            Role
                                        </label>
                                        <select x-model="posRoleId" name="instansi_role_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="posRoleId" required>
                                            <option value="">Select Role</option>
                                            @foreach($hierarchyRoles as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="posDesc">
                                            Description (Optional)
                                        </label>
                                        <textarea x-model="posDesc" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="posDesc" placeholder="Description..."></textarea>
                                    </div>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                        Create
                                    </button>
                                    <button @click="posModalOpen = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Edit Department Modal -->
                <div x-show="editDeptModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <form :action="editDeptAction" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Edit Department</h3>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="editDeptName">
                                            Department Name
                                        </label>
                                        <input x-model="editDeptName" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="editDeptName" type="text" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="editDeptDesc">
                                            Description (Optional)
                                        </label>
                                        <textarea x-model="editDeptDesc" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="editDeptDesc"></textarea>
                                    </div>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                        Update
                                    </button>
                                    <button @click="editDeptModalOpen = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Edit Position Modal -->
                <div x-show="editPosModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <form :action="editPosAction" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Edit Position</h3>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="editPosName">
                                            Position Name
                                        </label>
                                        <input x-model="editPosName" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="editPosName" type="text" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="editPosRoleId">
                                            Role
                                        </label>
                                        <select x-model="editPosRoleId" name="instansi_role_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="editPosRoleId" required>
                                            <option value="">Select Role</option>
                                            @foreach($hierarchyRoles as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="editPosDesc">
                                            Description (Optional)
                                        </label>
                                        <textarea x-model="editPosDesc" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="editPosDesc"></textarea>
                                    </div>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                        Update
                                    </button>
                                    <button @click="editPosModalOpen = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Create Role Modal -->
        <div x-show="roleModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('admin.roles.store') }}" method="POST">
                        @csrf
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Add New Role</h3>
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="roleName">
                                    Role Name
                                </label>
                                <input name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="roleName" type="text" placeholder="e.g. HR Manager" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="systemRole">
                                    System Role Type
                                </label>
                                <select name="system_role_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="systemRole" required>
                                    <option value="">Select System Role</option>
                                    @foreach($systemRoles as $sysRole)
                                        <option value="{{ $sysRole->id }}">{{ $sysRole->name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Determines base access level (Admin vs Employee)</p>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="roleDesc">
                                    Description (Optional)
                                </label>
                                <textarea name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="roleDesc" placeholder="Description..."></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="is_active" class="form-checkbox h-5 w-5 text-blue-600" checked>
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Active</span>
                                </label>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Create Role
                            </button>
                            <button @click="roleModalOpen = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Create Division Modal -->
        <div x-show="divisionModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('admin.divisions.store') }}" method="POST">
                        @csrf
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Add New Division</h3>
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="divName">
                                    Division Name
                                </label>
                                <input name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="divName" type="text" placeholder="e.g. Human Resources" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="divCode">
                                    Division Code
                                </label>
                                <input name="code" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="divCode" type="text" placeholder="e.g. HR" required pattern="[A-Z]+" title="Uppercase letters only">
                                <p class="text-xs text-gray-500 mt-1">Uppercase letters only (e.g., IT, HR, FIN)</p>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="divDesc">
                                    Description (Optional)
                                </label>
                                <textarea name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="divDesc" placeholder="Description..."></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="is_active" class="form-checkbox h-5 w-5 text-blue-600" checked>
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Active</span>
                                </label>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Create Division
                            </button>
                            <button @click="divisionModalOpen = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
