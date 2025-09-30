<x-superadmin-layout>
    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 md:mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">Package Management</h1>
                    <p class="mt-1 text-gray-600 dark:text-gray-400">Manage all packages in the system</p>
                </div>
                <a href="{{ route('superadmin.packages.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fa-solid fa-plus mr-2"></i>
                    Add New Package
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if($packages->count() > 0)
                <!-- Package Cards -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    @foreach($packages as $package)
                        <div class="bg-white dark:bg-gray-900 rounded-lg shadow">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $package->name }}
                                    </h3>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($package->is_active) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                        {{ $package->is_active ? 'Active' : 'Inactive' }}
                                    </span>
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
                                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        Rp {{ number_format($package->price, 0, ',', '.') }}
                                    </div>
                                </div>

                                <!-- Duration -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Duration (Days)
                                    </label>
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $package->duration_days ?? 'N/A' }} days
                                    </div>
                                </div>

                                <!-- Limits -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Max Employees
                                        </label>
                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                            {{ $package->max_employees }}
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Max Branches
                                        </label>
                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                            {{ $package->max_branches }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Features Summary -->
                                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                        Enabled Features
                                    </h4>
                                    <div class="text-xs text-gray-600 dark:text-gray-400">
                                        @if($package->features && count($package->features) > 0)
                                            <div class="flex flex-wrap gap-1">
                                                @php
                                                    $featureLabels = [
                                                        'attendance_management' => 'Attendance',
                                                        'payroll_management' => 'Payroll',
                                                        'advanced_reporting' => 'Reports',
                                                        'qr_code_attendance' => 'QR Code',
                                                        'gps_tracking' => 'GPS',
                                                        'face_recognition' => 'Face Rec',
                                                        'leave_management' => 'Leave Mgmt',
                                                        'mobile_app_access' => 'Mobile',
                                                        'web_dashboard' => 'Dashboard',
                                                        'email_notifications' => 'Email',
                                                        'sms_notifications' => 'SMS',
                                                        'api_integration' => 'API',
                                                        'custom_branding' => 'Branding',
                                                        'priority_support' => 'Priority',
                                                        'advanced_analytics' => 'Analytics',
                                                        'bulk_operations' => 'Bulk Ops',
                                                        'multi_company_support' => 'Multi-Co',
                                                        'custom_integrations' => 'Custom Int',
                                                        'dedicated_support' => 'Dedicated',
                                                        'on_premise_option' => 'On-Premise',
                                                        'basic_reporting' => 'Basic Rep'
                                                    ];
                                                @endphp
                                                @foreach($package->features as $feature)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        {{ $featureLabels[$feature] ?? ucwords(str_replace('_', ' ', $feature)) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">No features configured</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('superadmin.packages.show', $package) }}" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <i class="fa-solid fa-eye mr-1"></i>
                                            View
                                        </a>
                                        <a href="{{ route('superadmin.packages.edit', $package) }}" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <i class="fa-solid fa-edit mr-1"></i>
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('superadmin.packages.destroy', $package) }}" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this package?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                <i class="fa-solid fa-trash mr-1"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No packages</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new package.</p>
                    <div class="mt-6">
                        <a href="{{ route('superadmin.packages.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Add New Package
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-superadmin-layout>
