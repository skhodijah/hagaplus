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
                            <div class="p-6 flex-grow space-y-6">
                                <!-- Limits -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 dark:bg-gray-700/30 p-3 rounded-xl">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Employees</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $package->max_employees }}</p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700/30 p-3 rounded-xl">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Branches</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $package->max_branches }}</p>
                                    </div>
                                </div>

                                <!-- Features -->
                                <div>
                                    <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Included Features</p>
                                    <div class="flex flex-wrap gap-2">
                                        @if($package->features && count($package->features) > 0)
                                            @foreach(array_slice($package->features, 0, 5) as $feature)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-100 dark:border-blue-800">
                                                    {{ ucwords(str_replace('_', ' ', $feature)) }}
                                                </span>
                                            @endforeach
                                            @if(count($package->features) > 5)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-50 text-gray-600 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                                    +{{ count($package->features) - 5 }} more
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-sm text-gray-400 italic">No specific features</span>
                                        @endif
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
