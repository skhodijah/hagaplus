<x-superadmin-layout>
    <div class="py-6 md:py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 md:mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-2xl font-bold text-gray-600 dark:text-gray-300">{{ substr($user->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $user->name }}</h1>
                            <p class="text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('superadmin.users.edit', $user) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fa-solid fa-edit mr-2"></i>Edit User
                        </a>
                        <a href="{{ route('superadmin.users.index') }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fa-solid fa-arrow-left mr-2"></i>Back to Users
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- User Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white dark:bg-gray-900 rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Basic Information</h3>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Full Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Address</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone Number</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->phone ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Role</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @switch($user->role)
                                                @case('superadmin') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                @case('admin') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                                @case('employee') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @default bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                            @endswitch">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($user->is_active) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Member Since</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->created_at->format('M d, Y') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Instansi Information -->
                    @if($user->instansi)
                    <div class="bg-white dark:bg-gray-900 rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Instansi Information</h3>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Instansi Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->instansi->nama_instansi }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Instansi Status</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($user->instansi->is_active) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                            {{ $user->instansi->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Subscription Status</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @switch($user->instansi->status_langganan)
                                                @case('active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @case('inactive') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @case('suspended') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                @default bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                            @endswitch">
                                            {{ ucfirst($user->instansi->status_langganan ?? 'N/A') }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Joined Instansi</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->instansi->created_at->format('M d, Y') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    @endif

                    <!-- Employee Information (if applicable) -->
                    @if($user->employees->count() > 0)
                    <div class="bg-white dark:bg-gray-900 rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Employee Information</h3>
                        </div>
                        <div class="px-6 py-4">
                            @foreach($user->employees as $employee)
                            <div class="border-b border-gray-200 dark:border-gray-700 last:border-b-0 pb-4 last:pb-0">
                                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Employee ID</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $employee->employee_id }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Position</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $employee->position }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Department</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $employee->department }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Salary</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($employee->salary, 0, ',', '.') }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Hire Date</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $employee->hire_date->format('M d, Y') }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Employee Status</dt>
                                        <dd class="mt-1">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                @switch($employee->status)
                                                    @case('active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @case('inactive') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @case('terminated') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                    @default bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                                @endswitch">
                                                {{ ucfirst($employee->status) }}
                                            </span>
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-900 rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Quick Actions</h3>
                        </div>
                        <div class="px-6 py-4 space-y-3">
                            @if($user->role !== 'superadmin')
                                <form method="POST" action="{{ route('superadmin.users.toggle-status', $user) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors">
                                        <i class="fa-solid fa-{{ $user->is_active ? 'ban' : 'check' }} mr-2"></i>
                                        {{ $user->is_active ? 'Deactivate' : 'Activate' }} User
                                    </button>
                                </form>
                            @endif

                            <a href="mailto:{{ $user->email }}" class="block px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors">
                                <i class="fa-solid fa-envelope mr-2"></i>Send Email
                            </a>

                            @if($user->phone)
                                <a href="tel:{{ $user->phone }}" class="block px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors">
                                    <i class="fa-solid fa-phone mr-2"></i>Call User
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Account Statistics -->
                    <div class="bg-white dark:bg-gray-900 rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Account Statistics</h3>
                        </div>
                        <div class="px-6 py-4">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Total Logins</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $totalLogins ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Last Login</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $lastLogin ?? 'Never' }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Account Age</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    @if($user->role !== 'superadmin')
                    <div class="bg-white dark:bg-gray-900 rounded-lg shadow border border-red-200 dark:border-red-800">
                        <div class="px-6 py-4 border-b border-red-200 dark:border-red-800">
                            <h3 class="text-lg font-semibold text-red-900 dark:text-red-100">Danger Zone</h3>
                        </div>
                        <div class="px-6 py-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Once you delete this user, there is no going back. Please be certain.
                            </p>
                            <form method="POST" action="{{ route('superadmin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                    <i class="fa-solid fa-trash mr-2"></i>Delete User
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-superadmin-layout>