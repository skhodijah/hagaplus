<x-superadmin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Package Management</h1>
                    <p class="mt-2 text-gray-600">Manage all packages in the system</p>
                </div>
                <a href="{{ route('superadmin.packages.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add New Package
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 sm:p-6">
                    @if($packages->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Max Employees</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Max Branches</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($packages as $package)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $package->name }}</div>
                                                <div class="text-sm text-gray-500">{{ Str::limit($package->description, 50) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                Rp {{ number_format($package->price) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $package->max_employees }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $package->max_branches }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($package->is_active) bg-green-100 text-green-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ $package->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('superadmin.packages.show', $package) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                                    <a href="{{ route('superadmin.packages.edit', $package) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                    <form method="POST" action="{{ route('superadmin.packages.destroy', $package) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this package?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $packages->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No packages</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new package.</p>
                            <div class="mt-6">
                                <a href="{{ route('superadmin.packages.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Add New Package
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-superadmin-layout>
