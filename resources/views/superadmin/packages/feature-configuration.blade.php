<x-superadmin-layout>
    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">Feature Configuration</h1>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Configure features for each package (JSON format)</p>
            </div>

            @foreach($packages as $package)
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow mb-6">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            {{ $package->name }}
                        </h3>
                    </div>

                    <form action="{{ route('superadmin.packages.update-feature-configuration') }}" method="POST" class="p-6">
                        @csrf
                        <input type="hidden" name="package_id" value="{{ $package->id }}">

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Features Configuration (JSON)
                            </label>
                            <textarea name="features"
                                      rows="10"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300 text-sm font-mono"
                                      placeholder='{"attendance": true, "payroll": true, "reports": {"enabled": true, "max_exports": 10}}'>{{ $package->features ? json_encode($package->features, JSON_PRETTY_PRINT) : '' }}</textarea>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Enter features as JSON. Example: {"attendance": true, "payroll": {"enabled": true, "tax_calculation": true}}
                            </p>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fa-solid fa-save mr-2"></i>
                                Save Configuration
                            </button>
                        </div>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</x-superadmin-layout>