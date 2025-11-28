<x-admin-layout>
    <x-page-header title="{{ __('Organization Hierarchy') }}" class="mb-6">
        <div class="flex space-x-2">
            <a href="{{ route('admin.divisions.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                <i class="fas fa-sitemap mr-2"></i>Manage Divisions
            </a>
            <a href="{{ route('admin.hierarchy.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                <i class="fas fa-sync mr-2"></i>Refresh
            </a>
        </div>
    </x-page-header>

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

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @forelse ($divisions as $division)
            <div x-data="{ open: true }" class="mb-4">
                <!-- Division Header -->
                <div class="w-full flex justify-between items-center px-4 py-3 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-gray-700 dark:to-gray-600 rounded-lg shadow-sm border border-blue-100 dark:border-gray-600">
                    <button @click="open = !open" class="flex items-center flex-grow text-left focus:outline-none">
                        <i class="fas fa-building text-blue-600 dark:text-blue-400 mr-3"></i>
                        <span class="font-bold text-gray-900 dark:text-white text-lg">
                            {{ $division->name }}
                        </span>
                        @if($division->code)
                            <span class="ml-2 px-2 py-1 bg-blue-200 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-semibold rounded">
                                {{ $division->code }}
                            </span>
                        @endif
                        <i :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="ml-3 text-gray-600 dark:text-gray-300 text-sm"></i>
                    </button>
                    
                    <div class="flex items-center space-x-2">
                        <button @click="openAddDept({{ $division->id }})" class="text-xs bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-blue-600 dark:text-blue-400 px-3 py-1.5 rounded border border-blue-200 dark:border-gray-500 transition-colors shadow-sm">
                            <i class="fas fa-plus mr-1"></i> Add Dept
                        </button>
                    </div>
                </div>

                <!-- Departments List -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="mt-3 pl-4 md:pl-8 space-y-3">
                    @forelse ($division->departments as $department)
                        <div x-data="{ openDept: true }" class="border-l-2 border-gray-300 dark:border-gray-600 pl-4 relative">
                            <!-- Department Header -->
                            <div class="w-full flex justify-between items-center px-3 py-2 bg-gray-50 dark:bg-gray-750 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 group">
                                <button @click="openDept = !openDept" class="flex items-center flex-grow text-left focus:outline-none">
                                    <i class="fas fa-sitemap text-green-600 dark:text-green-400 mr-2"></i>
                                    <span class="font-semibold text-gray-800 dark:text-gray-200">
                                        {{ $department->name }}
                                    </span>
                                    <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">
                                        ({{ $department->positions->count() }} positions)
                                    </span>
                                    <i :class="openDept ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="ml-2 text-gray-400 text-xs"></i>
                                </button>

                                <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <button @click="openAddPos({{ $department->id }})" class="text-xs text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300" title="Add Position">
                                        <i class="fas fa-plus-circle"></i> Add Pos
                                    </button>
                                    <button @click="openEditDept({{ $department }})" class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="Edit Department">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.departments.destroy', $department->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this department?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" title="Delete Department">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Positions List -->
                            <div x-show="openDept"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 class="mt-2 pl-4 space-y-2">
                                @forelse ($department->positions as $position)
                                    <div class="flex items-center justify-between py-2 px-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-md hover:shadow-sm transition-shadow duration-200 group">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-user-tie text-purple-600 dark:text-purple-400"></i>
                                            <div>
                                                <span class="font-medium text-gray-900 dark:text-white">
                                                    {{ $position->name }}
                                                </span>
                                                @if ($position->instansiRole)
                                                    <span class="ml-2 px-2 py-0.5 text-xs bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 rounded-full font-medium">
                                                        {{ $position->instansiRole->name }}
                                                    </span>
                                                @else
                                                    <span class="ml-2 px-2 py-0.5 text-xs bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 rounded-full">No Role</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                            <button @click="openEditPos({{ $position }})" class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="Edit Position">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.positions.destroy', $position->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this position?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" title="Delete Position">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-sm text-gray-500 dark:text-gray-400 italic py-2 flex justify-between items-center group">
                                        <span><i class="fas fa-info-circle mr-1"></i>No positions yet.</span>
                                        <button @click="openAddPos({{ $department->id }})" class="text-xs text-green-600 hover:text-green-800 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <i class="fas fa-plus mr-1"></i>Add Position
                                        </button>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-gray-500 dark:text-gray-400 italic pl-4 py-2 flex justify-between items-center group">
                            <span><i class="fas fa-info-circle mr-1"></i>No departments yet.</span>
                            <button @click="openAddDept({{ $division->id }})" class="text-xs text-blue-600 hover:text-blue-800 opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="fas fa-plus mr-1"></i>Add Department
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <i class="fas fa-folder-open text-gray-400 text-5xl mb-4"></i>
                <p class="text-gray-500 dark:text-gray-400 text-lg">No divisions found.</p>
                <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">Create divisions to build your organization hierarchy.</p>
                <a href="{{ route('admin.divisions.index') }}" class="inline-block mt-4 text-blue-600 hover:underline">Go to Divisions Management</a>
            </div>
        @endforelse

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
                                    @foreach($roles as $role)
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
                                    @foreach($roles as $role)
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
</x-admin-layout>
