<x-superadmin-layout>
    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">Pricing Settings</h1>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Configure pricing, limits, and availability for all packages</p>
            </div>

            <form action="{{ route('superadmin.packages.update-pricing-settings') }}" method="POST">
                @csrf

                <!-- Package Cards -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    @foreach($packages as $package)
                        <div class="bg-white dark:bg-gray-900 rounded-lg shadow">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $package->name }}
                                    </h3>
                                    <label class="inline-flex items-center">
                                        <input type="hidden" name="packages[{{ $package->id }}][is_active]" value="0">
                                        <input type="checkbox"
                                               name="packages[{{ $package->id }}][is_active]"
                                               value="1"
                                               {{ $package->is_active ? 'checked' : '' }}
                                               class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                                    </label>
                                </div>
                                @if($package->description)
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $package->description }}
                                    </p>
                                @endif
                            </div>

                            <div class="p-6 space-y-4">
                                <!-- Price -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Price (IDR)
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="number"
                                               name="packages[{{ $package->id }}][price]"
                                               value="{{ $package->price }}"
                                               min="0"
                                               step="1000"
                                               class="pl-12 w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300"
                                               required>
                                    </div>
                                </div>

                                <!-- Duration -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Duration (Days)
                                    </label>
                                    <input type="number"
                                           name="packages[{{ $package->id }}][duration_days]"
                                           value="{{ $package->duration_days }}"
                                           min="1"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300"
                                           required>
                                </div>

                                <!-- Limits -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Max Employees
                                        </label>
                                        <input type="number"
                                               name="packages[{{ $package->id }}][max_employees]"
                                               value="{{ $package->max_employees }}"
                                               min="1"
                                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300"
                                               required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Max Branches
                                        </label>
                                        <input type="number"
                                               name="packages[{{ $package->id }}][max_branches]"
                                               value="{{ $package->max_branches }}"
                                               min="1"
                                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300"
                                               required>
                                    </div>
                                </div>

                                <!-- Features Summary -->
                                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                        Features Configuration
                                    </h4>
                                    <div class="text-xs text-gray-600 dark:text-gray-400">
                                        @if($package->features)
                                            <pre class="bg-gray-50 dark:bg-gray-800 p-2 rounded text-xs overflow-x-auto">{{ json_encode($package->features, JSON_PRETTY_PRINT) }}</pre>
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">No features configured</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Monthly Revenue Projection -->
                                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Monthly Revenue Projection
                                        </span>
                                        <span class="text-lg font-bold text-green-600 dark:text-green-400">
                                            Rp {{ number_format($package->price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>


                <!-- Save Button -->
                <div class="flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fa-solid fa-save mr-2"></i>
                        Save Pricing Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-superadmin-layout>