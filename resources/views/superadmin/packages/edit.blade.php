<x-superadmin-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Package</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Update package details and settings.</p>
            </div>

            <form method="POST" action="{{ route('superadmin.packages.update', $package) }}" class="space-y-8">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/20">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Basic Information</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">General details about the package.</p>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Package Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $package->name) }}" placeholder="e.g. Professional Plan"
                                   class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors @error('name') border-red-300 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                            <textarea name="description" id="description" rows="3" placeholder="Briefly describe what this package offers..."
                                      class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors @error('description') border-red-300 @enderror">{{ old('description', $package->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Price (IDR)</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="price" id="price" value="{{ old('price', $package->price) }}" min="0" step="0.01" placeholder="0"
                                           class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white pl-10 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors @error('price') border-red-300 @enderror">
                                </div>
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="duration_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Duration (Days)</label>
                                <input type="number" name="duration_days" id="duration_days" value="{{ old('duration_days', $package->duration_days) }}" min="1"
                                       class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors @error('duration_days') border-red-300 @enderror">
                                @error('duration_days')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Limits & Quotas -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/20">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Limits & Quotas</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Set the operational limits for this package.</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="max_employees" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Max Employees</label>
                                <input type="number" name="max_employees" id="max_employees" value="{{ old('max_employees', $package->max_employees) }}" min="1"
                                       class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors @error('max_employees') border-red-300 @enderror">
                                @error('max_employees')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="max_admins" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Max Admins</label>
                                <input type="number" name="max_admins" id="max_admins" value="{{ old('max_admins', $package->max_admins) }}" min="1"
                                       class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors @error('max_admins') border-red-300 @enderror">
                                @error('max_admins')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="max_branches" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Max Branches</label>
                                <input type="number" name="max_branches" id="max_branches" value="{{ old('max_branches', $package->max_branches) }}" min="1"
                                       class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors @error('max_branches') border-red-300 @enderror">
                                @error('max_branches')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status & Actions -->
                <div class="flex items-center justify-between pt-4">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $package->is_active) ? 'checked' : '' }} 
                               class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 w-5 h-5 transition-colors">
                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Active Package</span>
                    </label>

                    <div class="flex items-center space-x-4">
                        <a href="{{ route('superadmin.packages.index') }}" class="px-6 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-sm hover:shadow transition-all duration-200">
                            Update Package
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-superadmin-layout>
