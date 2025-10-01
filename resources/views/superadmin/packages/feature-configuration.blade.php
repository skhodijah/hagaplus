<x-superadmin-layout>
    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">Feature Configuration</h1>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Configure features for each package using the checkboxes below</p>
            </div>

            @php
                $availableFeatures = [
                    'Core Features' => [
                        'attendance_management' => 'Attendance Management',
                        'payroll_management' => 'Payroll Management',
                        'leave_management' => 'Leave Management',
                        'web_dashboard' => 'Web Dashboard',
                        'mobile_app_access' => 'Mobile App Access',
                    ],
                    'Attendance & Tracking' => [
                        'qr_code_attendance' => 'QR Code Attendance',
                        'gps_tracking' => 'GPS Tracking',
                        'face_recognition' => 'Face Recognition',
                    ],
                    'Reporting & Analytics' => [
                        'basic_reporting' => 'Basic Reporting',
                        'advanced_reporting' => 'Advanced Reporting',
                        'advanced_analytics' => 'Advanced Analytics',
                    ],
                    'Communication' => [
                        'email_notifications' => 'Email Notifications',
                        'sms_notifications' => 'SMS Notifications',
                    ],
                    'Integration & Advanced' => [
                        'api_integration' => 'API Integration',
                        'custom_branding' => 'Custom Branding',
                        'bulk_operations' => 'Bulk Operations',
                        'multi_company_support' => 'Multi-Company Support',
                        'custom_integrations' => 'Custom Integrations',
                        'on_premise_option' => 'On-Premise Option',
                    ],
                    'Support' => [
                        'priority_support' => 'Priority Support',
                        'dedicated_support' => 'Dedicated Support',
                    ],
                ];
            @endphp

            @foreach($packages as $package)
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow mb-6">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            {{ $package->name }}
                        </h3>
                        @if($package->description)
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ $package->description }}
                            </p>
                        @endif
                    </div>

                    <form action="{{ route('superadmin.packages.update-feature-configuration') }}" method="POST" class="p-6">
                        @csrf
                        <input type="hidden" name="package_id" value="{{ $package->id }}">

                        <div class="space-y-6">
                            @foreach($availableFeatures as $category => $features)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-3">
                                        {{ $category }}
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                        @foreach($features as $featureKey => $featureName)
                                            <label class="inline-flex items-center">
                                                <input type="checkbox"
                                                       name="features[{{ $featureKey }}]"
                                                       value="1"
                                                       {{ in_array($featureKey, $package->features ?? []) ? 'checked' : '' }}
                                                       class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700">
                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                                    {{ $featureName }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex justify-end mt-6">
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