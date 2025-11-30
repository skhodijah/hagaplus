<x-superadmin-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-10">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Packages</h1>
                    <p class="mt-2 text-gray-500 dark:text-gray-400">Manage subscription packages and features.</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('superadmin.packages.create') }}" class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-sm transition-all duration-200">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Create Package
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-8 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-xl flex items-center">
                    <i class="fa-solid fa-check-circle mr-3 text-green-500"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($packages->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                    @foreach($packages as $package)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow duration-300 flex flex-col h-full">
                            <!-- Card Header -->
                            <div class="p-6 border-b border-gray-50 dark:border-gray-700/50">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $package->name }}</h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $package->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ $package->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <div class="flex items-baseline">
                                    <span class="text-3xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                                    <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">/ {{ $package->duration_days }} days</span>
                                </div>
                                @if($package->description)
                                    <p class="mt-3 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">{{ $package->description }}</p>
                                @endif
                            </div>

                            <!-- Card Body -->
                            <div class="p-6 flex-grow">
                                <!-- Limits -->
                                <div class="grid grid-cols-3 gap-3">
                                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 p-4 rounded-xl border border-blue-200 dark:border-blue-800">
                                        <div class="flex items-center justify-between mb-2">
                                            <i class="fa-solid fa-users text-blue-600 dark:text-blue-400"></i>
                                        </div>
                                        <p class="text-xs text-blue-700 dark:text-blue-300 uppercase tracking-wider mb-1">Employees</p>
                                        <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $package->max_employees }}</p>
                                    </div>
                                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 p-4 rounded-xl border border-purple-200 dark:border-purple-800">
                                        <div class="flex items-center justify-between mb-2">
                                            <i class="fa-solid fa-user-shield text-purple-600 dark:text-purple-400"></i>
                                        </div>
                                        <p class="text-xs text-purple-700 dark:text-purple-300 uppercase tracking-wider mb-1">Admins</p>
                                        <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">{{ $package->max_admins }}</p>
                                    </div>
                                    <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 p-4 rounded-xl border border-green-200 dark:border-green-800">
                                        <div class="flex items-center justify-between mb-2">
                                            <i class="fa-solid fa-building text-green-600 dark:text-green-400"></i>
                                        </div>
                                        <p class="text-xs text-green-700 dark:text-green-300 uppercase tracking-wider mb-1">Branches</p>
                                        <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ $package->max_branches }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Footer -->
                            <div class="p-6 border-t border-gray-50 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-800/50 rounded-b-2xl">
                                <div class="flex space-x-3">
                                    <a href="{{ route('superadmin.packages.show', $package) }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 hover:text-blue-600 dark:hover:text-blue-400 transition-colors shadow-sm">
                                        <i class="fa-solid fa-eye mr-2"></i> View
                                    </a>
                                    <a href="{{ route('superadmin.packages.edit', $package) }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 hover:text-blue-600 dark:hover:text-blue-400 transition-colors shadow-sm">
                                        <i class="fa-solid fa-pen-to-square mr-2"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('superadmin.packages.destroy', $package) }}" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this package?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed" {{ $package->name === 'TRIAL' ? 'disabled' : '' }}>
                                            <i class="fa-solid fa-trash-can mr-2"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $packages->links() }}
                </div>
            @else
                <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900/30 mb-4">
                        <i class="fa-solid fa-box-open text-3xl text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">No Packages Found</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-sm mx-auto">Get started by creating your first subscription package.</p>
                    <div class="mt-6">
                        <a href="{{ route('superadmin.packages.create') }}" class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-sm transition-all duration-200">
                            <i class="fa-solid fa-plus mr-2"></i>
                            Create Package
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-superadmin-layout>
