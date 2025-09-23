<x-superadmin-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Package Details</h1>
                    <p class="mt-2 text-gray-600">View details of the selected package</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('superadmin.packages.edit', $package) }}" 
                       class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Edit Package
                    </a>
                    <a href="{{ route('superadmin.packages.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Back
                    </a>
                </div>
            </div>

            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-6 py-5 sm:p-8">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-8">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Package Name</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $package->name }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Price</dt>
                            <dd class="mt-1 text-lg text-gray-900">Rp {{ number_format($package->price) }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Max Employees</dt>
                            <dd class="mt-1 text-lg text-gray-900">{{ $package->max_employees }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Max Branches</dt>
                            <dd class="mt-1 text-lg text-gray-900">{{ $package->max_branches }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                                    @if($package->is_active) bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $package->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </dd>
                        </div>

                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-gray-900">{{ $package->description }}</dd>
                        </div>

                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Features</dt>
                            <dd class="mt-2">
                                @if(!empty($package->features) && is_array($package->features))
                                    <ul class="list-disc list-inside text-gray-900 space-y-1">
                                        @foreach($package->features as $feature)
                                            <li class="capitalize">{{ str_replace('_', ' ', $feature) }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-gray-500">No features specified</p>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <form method="POST" action="{{ route('superadmin.packages.destroy', $package) }}" onsubmit="return confirm('Are you sure you want to delete this package?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Delete Package
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-superadmin-layout>
