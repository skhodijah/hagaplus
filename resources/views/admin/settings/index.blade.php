<x-admin-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">System Settings</h1>
                <div class="flex space-x-3">
                    <button type="button" id="reset-all" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                        <i class="fa-solid fa-undo mr-2"></i>Reset All
                    </button>
                    <button type="submit" form="settings-form" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fa-solid fa-save mr-2"></i>Save Changes
                    </button>
                </div>
            </div>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Configure system settings, attendance policies, and company information.
            </p>
        </div>

        <form id="settings-form" method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Settings Tabs -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        @foreach($categories as $key => $label)
                            <button type="button"
                                    class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm
                                           {{ $loop->first ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}"
                                    data-tab="{{ $key }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </nav>
                </div>

                <div class="p-6">
                    @foreach($categories as $categoryKey => $categoryLabel)
                        <div class="tab-content {{ $loop->first ? '' : 'hidden' }}" data-tab="{{ $categoryKey }}">
                            <div class="space-y-6">
                                @foreach($allSettings as $key => $setting)
                                    @if($setting['config']['category'] === $categoryKey)
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                            <div class="md:col-span-1">
                                                <label for="{{ $key }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    {{ $setting['config']['label'] }}
                                                </label>
                                                @if(isset($setting['config']['description']))
                                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $setting['config']['description'] }}</p>
                                                @endif
                                            </div>

                                            <div class="md:col-span-2">
                                                @switch($setting['config']['type'])
                                                    @case('text')
                                                        <input type="text" id="{{ $key }}" name="{{ $key }}" value="{{ $setting['value'] }}"
                                                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                                               {{ isset($setting['config']['placeholder']) ? 'placeholder="' . $setting['config']['placeholder'] . '"' : '' }}>
                                                        @break

                                                    @case('textarea')
                                                        <textarea id="{{ $key }}" name="{{ $key }}" rows="3"
                                                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">{{ $setting['value'] }}</textarea>
                                                        @break

                                                    @case('email')
                                                        <input type="email" id="{{ $key }}" name="{{ $key }}" value="{{ $setting['value'] }}"
                                                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                                        @break

                                                    @case('number')
                                                        <input type="number" id="{{ $key }}" name="{{ $key }}" value="{{ $setting['value'] }}"
                                                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                                               {{ isset($setting['config']['min']) ? 'min="' . $setting['config']['min'] . '"' : '' }}
                                                               {{ isset($setting['config']['max']) ? 'max="' . $setting['config']['max'] . '"' : '' }}
                                                               {{ isset($setting['config']['step']) ? 'step="' . $setting['config']['step'] . '"' : '' }}>
                                                        @break

                                                    @case('boolean')
                                                        <div class="flex items-center">
                                                            <input type="checkbox" id="{{ $key }}" name="{{ $key }}" value="1"
                                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                                                   {{ $setting['value'] ? 'checked' : '' }}>
                                                            <label for="{{ $key }}" class="ml-2 block text-sm text-gray-900 dark:text-white">
                                                                {{ $setting['value'] ? 'Enabled' : 'Disabled' }}
                                                            </label>
                                                        </div>
                                                        @break

                                                    @case('select')
                                                        <select id="{{ $key }}" name="{{ $key }}"
                                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                                            @foreach($setting['config']['options'] as $optionValue => $optionLabel)
                                                                <option value="{{ $optionValue }}" {{ $setting['value'] == $optionValue ? 'selected' : '' }}>
                                                                    {{ $optionLabel }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @break

                                                    @case('time')
                                                        <input type="time" id="{{ $key }}" name="{{ $key }}" value="{{ $setting['value'] }}"
                                                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                                        @break

                                                    @case('file')
                                                        <div class="space-y-2">
                                                            @if($setting['value'])
                                                                <div class="flex items-center space-x-2">
                                                                    <img src="{{ asset('storage/' . $setting['value']) }}" alt="Current logo" class="w-12 h-12 object-cover rounded">
                                                                    <span class="text-sm text-gray-600 dark:text-gray-400">Current logo</span>
                                                                </div>
                                                            @endif
                                                            <input type="file" id="{{ $key }}" name="{{ $key }}"
                                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                                                   {{ isset($setting['config']['accept']) ? 'accept="' . $setting['config']['accept'] . '"' : '' }}>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">Upload a new logo (JPEG, PNG, JPG, GIF, SVG - Max 2MB)</p>
                                                        </div>
                                                        @break

                                                    @default
                                                        <input type="text" id="{{ $key }}" name="{{ $key }}" value="{{ $setting['value'] }}"
                                                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                                @endswitch
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                <!-- Reset Category Button -->
                                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <button type="button" class="reset-category-btn bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-sm"
                                            data-category="{{ $categoryKey }}">
                                        <i class="fa-solid fa-undo mr-2"></i>Reset {{ $categoryLabel }} to Defaults
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </form>

        <!-- Settings Summary -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Settings Summary</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fa-solid fa-building text-blue-600 dark:text-blue-400 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Company Settings</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ count(array_filter($allSettings, fn($s) => $s['config']['category'] === 'company')) }} configured</p>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fa-solid fa-calendar-check text-green-600 dark:text-green-400 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Attendance Settings</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ count(array_filter($allSettings, fn($s) => $s['config']['category'] === 'attendance')) }} configured</p>
                        </div>
                    </div>
                </div>

                <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fa-solid fa-cog text-purple-600 dark:text-purple-400 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">System Settings</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ count(array_filter($allSettings, fn($s) => $s['config']['category'] === 'system')) }} configured</p>
                        </div>
                    </div>
                </div>

                <div class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fa-solid fa-money-bill-wave text-orange-600 dark:text-orange-400 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Payroll Settings</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ count(array_filter($allSettings, fn($s) => $s['config']['category'] === 'payroll')) }} configured</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/admin/settings.js') }}"></script>
</x-admin-layout>