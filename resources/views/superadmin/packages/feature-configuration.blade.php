<x-superadmin-layout>
    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">Feature Configuration</h1>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Configure which features are enabled for each package and their limits</p>
            </div>

            <!-- Package Tabs -->
            <div class="mb-6">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        @foreach($packages as $index => $package)
                            <button type="button"
                                    onclick="switchPackage({{ $package->id }})"
                                    class="tab-button whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm
                                           {{ $index === 0 ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
                                    data-package-id="{{ $package->id }}">
                                {{ $package->name }}
                            </button>
                        @endforeach
                    </nav>
                </div>
            </div>

            @foreach($packages as $index => $package)
                <form id="package-form-{{ $package->id }}"
                      action="{{ route('superadmin.packages.update-feature-configuration') }}"
                      method="POST"
                      class="package-form {{ $index === 0 ? '' : 'hidden' }}">
                    @csrf
                    <input type="hidden" name="package_id" value="{{ $package->id }}">

                    <!-- Features by Category -->
                    @php
                        $categories = $features->groupBy('category');
                    @endphp

                    @foreach($categories as $category => $categoryFeatures)
                        <div class="bg-white dark:bg-gray-900 rounded-lg shadow mb-6">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 capitalize">
                                    {{ str_replace('_', ' ', $category) }} Features
                                </h3>
                            </div>

                            <div class="p-6 space-y-4">
                                @foreach($categoryFeatures as $feature)
                                    @php
                                        $packageFeature = $package->packageFeatures->where('feature_id', $feature->id)->first();
                                        $isEnabled = $packageFeature ? $packageFeature->is_enabled : false;
                                        $limits = $packageFeature ? $packageFeature->limits : null;
                                        $config = $packageFeature ? $packageFeature->config_override : null;
                                    @endphp

                                    <div class="flex items-start space-x-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                        <div class="flex-shrink-0">
                                            <input type="hidden"
                                                   name="features[{{ $feature->id }}][enabled]"
                                                   value="0">
                                            <input type="checkbox"
                                                   name="features[{{ $feature->id }}][enabled]"
                                                   value="1"
                                                   {{ $isEnabled ? 'checked' : '' }}
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $feature->name }}
                                                </h4>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                             {{ $feature->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                                    {{ $feature->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>

                                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                {{ $feature->description }}
                                            </p>

                                            @if($feature->config)
                                                <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-md">
                                                    <h5 class="text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-2">
                                                        Default Configuration
                                                    </h5>
                                                    <pre class="text-xs text-gray-600 dark:text-gray-400">{{ json_encode($feature->config, JSON_PRETTY_PRINT) }}</pre>
                                                </div>
                                            @endif

                                            <!-- Feature-Specific Configuration -->
                                            <div class="mt-3">
                                                @if($feature->slug === 'qr_attendance')
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                Max Distance (meters)
                                                            </label>
                                                            <input type="number"
                                                                   name="features[{{ $feature->id }}][limits][max_distance]"
                                                                   value="{{ $limits['max_distance'] ?? 10 }}"
                                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300 text-sm">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                Refresh Interval (seconds)
                                                            </label>
                                                            <input type="number"
                                                                   name="features[{{ $feature->id }}][limits][refresh_interval]"
                                                                   value="{{ $limits['refresh_interval'] ?? 300 }}"
                                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300 text-sm">
                                                        </div>
                                                    </div>
                                                @elseif($feature->slug === 'gps_attendance')
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                Radius Tolerance (meters)
                                                            </label>
                                                            <input type="number"
                                                                   name="features[{{ $feature->id }}][limits][radius_tolerance]"
                                                                   value="{{ $limits['radius_tolerance'] ?? 100 }}"
                                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300 text-sm">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                Accuracy Required (meters)
                                                            </label>
                                                            <input type="number"
                                                                   name="features[{{ $feature->id }}][limits][accuracy_required]"
                                                                   value="{{ $limits['accuracy_required'] ?? 50 }}"
                                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300 text-sm">
                                                        </div>
                                                    </div>
                                                @elseif($feature->slug === 'face_recognition')
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                Max Attempts
                                                            </label>
                                                            <input type="number"
                                                                   name="features[{{ $feature->id }}][limits][max_attempts]"
                                                                   value="{{ $limits['max_attempts'] ?? 3 }}"
                                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300 text-sm">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                Confidence Threshold
                                                            </label>
                                                            <input type="number"
                                                                   step="0.01"
                                                                   min="0"
                                                                   max="1"
                                                                   name="features[{{ $feature->id }}][limits][confidence_threshold]"
                                                                   value="{{ $limits['confidence_threshold'] ?? 0.85 }}"
                                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300 text-sm">
                                                        </div>
                                                    </div>
                                                @elseif($feature->slug === 'shift_management')
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                Max Shifts Per Day
                                                            </label>
                                                            <input type="number"
                                                                   name="features[{{ $feature->id }}][limits][max_shifts_per_day]"
                                                                   value="{{ $limits['max_shifts_per_day'] ?? 3 }}"
                                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300 text-sm">
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="hidden"
                                                                   name="features[{{ $feature->id }}][limits][overlap_allowed]"
                                                                   value="0">
                                                            <input type="checkbox"
                                                                   name="features[{{ $feature->id }}][limits][overlap_allowed]"
                                                                   value="1"
                                                                   {{ ($limits['overlap_allowed'] ?? false) ? 'checked' : '' }}
                                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                            <label class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                                Allow Shift Overlap
                                                            </label>
                                                        </div>
                                                    </div>
                                                @elseif($feature->slug === 'leave_management')
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                Max Leave Days Per Year
                                                            </label>
                                                            <input type="number"
                                                                   name="features[{{ $feature->id }}][limits][max_leave_days]"
                                                                   value="{{ $limits['max_leave_days'] ?? 12 }}"
                                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300 text-sm">
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="hidden"
                                                                   name="features[{{ $feature->id }}][limits][approval_required]"
                                                                   value="0">
                                                            <input type="checkbox"
                                                                   name="features[{{ $feature->id }}][limits][approval_required]"
                                                                   value="1"
                                                                   {{ ($limits['approval_required'] ?? true) ? 'checked' : '' }}
                                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                            <label class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                                Approval Required
                                                            </label>
                                                        </div>
                                                    </div>
                                                @elseif($feature->slug === 'payroll_processing')
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div class="flex items-center">
                                                            <input type="hidden"
                                                                   name="features[{{ $feature->id }}][limits][tax_enabled]"
                                                                   value="0">
                                                            <input type="checkbox"
                                                                   name="features[{{ $feature->id }}][limits][tax_enabled]"
                                                                   value="1"
                                                                   {{ ($limits['tax_enabled'] ?? true) ? 'checked' : '' }}
                                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                            <label class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                                Tax Calculation Enabled
                                                            </label>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="hidden"
                                                                   name="features[{{ $feature->id }}][limits][auto_calculate]"
                                                                   value="0">
                                                            <input type="checkbox"
                                                                   name="features[{{ $feature->id }}][limits][auto_calculate]"
                                                                   value="1"
                                                                   {{ ($limits['auto_calculate'] ?? true) ? 'checked' : '' }}
                                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                            <label class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                                Auto Calculate Payroll
                                                            </label>
                                                        </div>
                                                    </div>
                                                @elseif($feature->slug === 'overtime_calculation')
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                Minimum Minutes for Overtime
                                                            </label>
                                                            <input type="number"
                                                                   name="features[{{ $feature->id }}][limits][minimum_minutes]"
                                                                   value="{{ $limits['minimum_minutes'] ?? 30 }}"
                                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300 text-sm">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                Rate Multiplier
                                                            </label>
                                                            <input type="number"
                                                                   step="0.1"
                                                                   name="features[{{ $feature->id }}][limits][rate_multiplier]"
                                                                   value="{{ $limits['rate_multiplier'] ?? 1.5 }}"
                                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300 text-sm">
                                                        </div>
                                                    </div>
                                                @elseif($feature->slug === 'basic_reports')
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                Exports Per Month
                                                            </label>
                                                            <input type="number"
                                                                   name="features[{{ $feature->id }}][limits][exports_per_month]"
                                                                   value="{{ $limits['exports_per_month'] ?? 10 }}"
                                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300 text-sm">
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="hidden"
                                                                   name="features[{{ $feature->id }}][limits][schedule_enabled]"
                                                                   value="0">
                                                            <input type="checkbox"
                                                                   name="features[{{ $feature->id }}][limits][schedule_enabled]"
                                                                   value="1"
                                                                   {{ ($limits['schedule_enabled'] ?? false) ? 'checked' : '' }}
                                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                            <label class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                                Scheduled Reports Enabled
                                                            </label>
                                                        </div>
                                                    </div>
                                                @elseif($feature->slug === 'advanced_reports')
                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                        <div class="flex items-center">
                                                            <input type="hidden"
                                                                   name="features[{{ $feature->id }}][limits][dashboard]"
                                                                   value="0">
                                                            <input type="checkbox"
                                                                   name="features[{{ $feature->id }}][limits][dashboard]"
                                                                   value="1"
                                                                   {{ ($limits['dashboard'] ?? true) ? 'checked' : '' }}
                                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                            <label class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                                Dashboard Access
                                                            </label>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="hidden"
                                                                   name="features[{{ $feature->id }}][limits][charts_enabled]"
                                                                   value="0">
                                                            <input type="checkbox"
                                                                   name="features[{{ $feature->id }}][limits][charts_enabled]"
                                                                   value="1"
                                                                   {{ ($limits['charts_enabled'] ?? true) ? 'checked' : '' }}
                                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                            <label class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                                Charts Enabled
                                                            </label>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="hidden"
                                                                   name="features[{{ $feature->id }}][limits][custom_filters]"
                                                                   value="0">
                                                            <input type="checkbox"
                                                                   name="features[{{ $feature->id }}][limits][custom_filters]"
                                                                   value="1"
                                                                   {{ ($limits['custom_filters'] ?? true) ? 'checked' : '' }}
                                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                            <label class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                                Custom Filters
                                                            </label>
                                                        </div>
                                                    </div>
                                                @elseif($feature->slug === 'api_access')
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                Rate Limit (requests/hour)
                                                            </label>
                                                            <input type="number"
                                                                   name="features[{{ $feature->id }}][limits][rate_limit]"
                                                                   value="{{ $limits['rate_limit'] ?? 1000 }}"
                                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300 text-sm">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                Authentication Method
                                                            </label>
                                                            <select name="features[{{ $feature->id }}][limits][authentication]"
                                                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300 text-sm">
                                                                <option value="oauth2" {{ ($limits['authentication'] ?? 'oauth2') === 'oauth2' ? 'selected' : '' }}>OAuth 2.0</option>
                                                                <option value="apikey" {{ ($limits['authentication'] ?? 'oauth2') === 'apikey' ? 'selected' : '' }}>API Key</option>
                                                                <option value="basic" {{ ($limits['authentication'] ?? 'oauth2') === 'basic' ? 'selected' : '' }}>Basic Auth</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                @elseif($feature->slug === 'custom_branding')
                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                        <div class="flex items-center">
                                                            <input type="hidden"
                                                                   name="features[{{ $feature->id }}][limits][logo_upload]"
                                                                   value="0">
                                                            <input type="checkbox"
                                                                   name="features[{{ $feature->id }}][limits][logo_upload]"
                                                                   value="1"
                                                                   {{ ($limits['logo_upload'] ?? true) ? 'checked' : '' }}
                                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                            <label class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                                Logo Upload
                                                            </label>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="hidden"
                                                                   name="features[{{ $feature->id }}][limits][css_override]"
                                                                   value="0">
                                                            <input type="checkbox"
                                                                   name="features[{{ $feature->id }}][limits][css_override]"
                                                                   value="1"
                                                                   {{ ($limits['css_override'] ?? true) ? 'checked' : '' }}
                                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                            <label class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                                CSS Override
                                                            </label>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="hidden"
                                                                   name="features[{{ $feature->id }}][limits][color_schemes]"
                                                                   value="0">
                                                            <input type="checkbox"
                                                                   name="features[{{ $feature->id }}][limits][color_schemes]"
                                                                   value="1"
                                                                   {{ ($limits['color_schemes'] ?? true) ? 'checked' : '' }}
                                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                            <label class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                                Color Schemes
                                                            </label>
                                                        </div>
                                                    </div>
                                                @elseif($feature->slug === 'multi_branch')
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                Max Branches
                                                            </label>
                                                            <input type="number"
                                                                   name="features[{{ $feature->id }}][limits][max_branches]"
                                                                   value="{{ $limits['max_branches'] ?? 50 }}"
                                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300 text-sm">
                                                        </div>
                                                        <div class="flex items-center">
                                                            <input type="hidden"
                                                                   name="features[{{ $feature->id }}][limits][central_management]"
                                                                   value="0">
                                                            <input type="checkbox"
                                                                   name="features[{{ $feature->id }}][limits][central_management]"
                                                                   value="1"
                                                                   {{ ($limits['central_management'] ?? true) ? 'checked' : '' }}
                                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                            <label class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                                Central Management
                                                            </label>
                                                        </div>
                                                    </div>
                                                @else
                                                    <!-- Generic JSON configuration for unknown features -->
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                Limits (JSON)
                                                            </label>
                                                            <textarea name="features[{{ $feature->id }}][limits]"
                                                                      rows="2"
                                                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300 text-xs"
                                                                      placeholder='{"max_employees": 50}'>{{ $limits ? json_encode($limits, JSON_PRETTY_PRINT) : '' }}</textarea>
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                Custom Config (JSON)
                                                            </label>
                                                            <textarea name="features[{{ $feature->id }}][config]"
                                                                      rows="2"
                                                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300 text-xs"
                                                                      placeholder='{"custom_setting": "value"}'>{{ $config ? json_encode($config, JSON_PRETTY_PRINT) : '' }}</textarea>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <!-- Save Button -->
                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fa-solid fa-save mr-2"></i>
                            Save Configuration
                        </button>
                    </div>
                </form>
            @endforeach
        </div>
    </div>

    <script>
        function switchPackage(packageId) {
            // Hide all forms
            document.querySelectorAll('.package-form').forEach(form => {
                form.classList.add('hidden');
            });

            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(tab => {
                tab.classList.remove('border-blue-500', 'text-blue-600', 'dark:text-blue-400');
                tab.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
            });

            // Show selected form
            document.getElementById('package-form-' + packageId).classList.remove('hidden');

            // Add active class to selected tab
            document.querySelector(`[data-package-id="${packageId}"]`).classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
            document.querySelector(`[data-package-id="${packageId}"]`).classList.add('border-blue-500', 'text-blue-600', 'dark:text-blue-400');
        }
    </script>
</x-superadmin-layout>