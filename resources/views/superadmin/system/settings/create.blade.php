<x-superadmin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create Setting') }}
            </h2>
            <a href="{{ route('superadmin.system.settings.index') }}"
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i>Back to Settings
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <form method="POST" action="{{ route('superadmin.system.settings.store') }}" class="p-6">
                    @csrf

                    <!-- Key -->
                    <div class="mb-4">
                        <label for="key" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Key <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="key" id="key" value="{{ old('key') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('key') border-red-500 @enderror"
                               placeholder="e.g., app_name, email_from_address" required>
                        @error('key')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Unique identifier for this setting (use snake_case).</p>
                    </div>

                    <!-- Type -->
                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Type <span class="text-red-500">*</span>
                        </label>
                        <select name="type" id="type"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('type') border-red-500 @enderror" required>
                            <option value="string" {{ old('type') == 'string' ? 'selected' : '' }}>String</option>
                            <option value="integer" {{ old('type') == 'integer' ? 'selected' : '' }}>Integer</option>
                            <option value="boolean" {{ old('type') == 'boolean' ? 'selected' : '' }}>Boolean</option>
                            <option value="float" {{ old('type') == 'float' ? 'selected' : '' }}>Float</option>
                            <option value="json" {{ old('type') == 'json' ? 'selected' : '' }}>JSON</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Value -->
                    <div class="mb-4">
                        <label for="value" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Value
                        </label>
                        <div id="value-inputs">
                            <!-- String/Text Input (default) -->
                            <textarea name="value" id="value-string" rows="3" value="{{ old('value') }}"
                                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('value') border-red-500 @enderror"
                                      placeholder="Enter the setting value">{{ old('value') }}</textarea>

                            <!-- Boolean Input -->
                            <select name="value_boolean" id="value-boolean" style="display: none;"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="1" {{ old('value') == '1' ? 'selected' : '' }}>True</option>
                                <option value="0" {{ old('value') == '0' ? 'selected' : '' }}>False</option>
                            </select>

                            <!-- JSON Input -->
                            <textarea name="value_json" id="value-json" rows="5" style="display: none;"
                                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono text-sm"
                                      placeholder='{"key": "value"}'></textarea>
                        </div>
                        @error('value')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400" id="value-help">
                            Enter the value for this setting.
                        </p>
                    </div>

                    <!-- Group -->
                    <div class="mb-4">
                        <label for="group" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Group <span class="text-red-500">*</span>
                        </label>
                        <select name="group" id="group"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('group') border-red-500 @enderror" required>
                            <option value="general" {{ old('group') == 'general' ? 'selected' : '' }}>General</option>
                            <option value="system" {{ old('group') == 'system' ? 'selected' : '' }}>System</option>
                            <option value="email" {{ old('group') == 'email' ? 'selected' : '' }}>Email</option>
                            <option value="security" {{ old('group') == 'security' ? 'selected' : '' }}>Security</option>
                            <option value="appearance" {{ old('group') == 'appearance' ? 'selected' : '' }}>Appearance</option>
                            <option value="performance" {{ old('group') == 'performance' ? 'selected' : '' }}>Performance</option>
                        </select>
                        @error('group')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Group this setting belongs to for organization.</p>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="3" value="{{ old('description') }}"
                                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('description') border-red-500 @enderror"
                                  placeholder="Describe what this setting does...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Optional description to help understand this setting.</p>
                    </div>

                    <!-- Is Public -->
                    <div class="mb-6">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_public" id="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <label for="is_public" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                Make this setting public (accessible via API)
                            </label>
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Public settings can be accessed without authentication.</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('superadmin.system.settings.index') }}"
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Cancel
                        </a>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-save mr-2"></i>Create Setting
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('type').addEventListener('change', function() {
            const type = this.value;
            const valueInputs = document.getElementById('value-inputs');
            const valueHelp = document.getElementById('value-help');

            // Hide all inputs first
            valueInputs.querySelectorAll('textarea, select').forEach(el => {
                el.style.display = 'none';
                el.removeAttribute('name');
            });

            // Show appropriate input based on type
            switch(type) {
                case 'boolean':
                    document.getElementById('value-boolean').style.display = 'block';
                    document.getElementById('value-boolean').setAttribute('name', 'value');
                    valueHelp.textContent = 'Select true or false for this boolean setting.';
                    break;
                case 'json':
                    document.getElementById('value-json').style.display = 'block';
                    document.getElementById('value-json').setAttribute('name', 'value');
                    valueHelp.textContent = 'Enter valid JSON data for this setting.';
                    break;
                case 'integer':
                    document.getElementById('value-string').style.display = 'block';
                    document.getElementById('value-string').setAttribute('name', 'value');
                    document.getElementById('value-string').setAttribute('type', 'number');
                    valueHelp.textContent = 'Enter an integer value for this setting.';
                    break;
                case 'float':
                    document.getElementById('value-string').style.display = 'block';
                    document.getElementById('value-string').setAttribute('name', 'value');
                    document.getElementById('value-string').setAttribute('type', 'number');
                    document.getElementById('value-string').setAttribute('step', '0.01');
                    valueHelp.textContent = 'Enter a decimal value for this setting.';
                    break;
                default: // string
                    document.getElementById('value-string').style.display = 'block';
                    document.getElementById('value-string').setAttribute('name', 'value');
                    document.getElementById('value-string').removeAttribute('type');
                    document.getElementById('value-string').removeAttribute('step');
                    valueHelp.textContent = 'Enter the text value for this setting.';
            }
        });

        // Trigger change event on page load to set initial state
        document.getElementById('type').dispatchEvent(new Event('change'));
    </script>
</x-superadmin-layout>