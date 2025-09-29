<x-superadmin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Setting Details: :key', ['key' => $setting->key]) }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('superadmin.system.settings.edit', $setting) }}"
                   class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <button onclick="deleteSetting({{ $setting->id }}, '{{ $setting->key }}')"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
                <a href="{{ route('superadmin.system.settings.index') }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Settings
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Setting Header -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ $setting->key }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Created {{ $setting->created_at->format('F d, Y \a\t H:i:s') }}
                                    @if($setting->updated_at != $setting->created_at)
                                        • Updated {{ $setting->updated_at->format('F d, Y \a\t H:i:s') }}
                                    @endif
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($setting->type === 'string') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                    @elseif($setting->type === 'integer') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                    @elseif($setting->type === 'boolean') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                    @elseif($setting->type === 'float') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                    @elseif($setting->type === 'json') bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300
                                    @endif">
                                    {{ ucfirst($setting->type) }}
                                </span>
                                @if($setting->is_public)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                        <i class="fas fa-globe mr-1"></i>Public
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300">
                                        Private
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Setting Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">
                                <i class="fas fa-info-circle mr-2"></i>Basic Information
                            </h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Key:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100 font-mono">{{ $setting->key }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Type:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ ucfirst($setting->type) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Group:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ ucfirst($setting->group) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Public:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $setting->is_public ? 'Yes' : 'No' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Metadata -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">
                                <i class="fas fa-calendar mr-2"></i>Metadata
                            </h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Created:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $setting->created_at->format('M d, Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Updated:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $setting->updated_at->format('M d, Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">ID:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $setting->id }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($setting->description)
                        <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">
                                <i class="fas fa-file-alt mr-2"></i>Description
                            </h4>
                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                {{ $setting->description }}
                            </p>
                        </div>
                    @endif

                    <!-- Value Display -->
                    <div class="mt-6 bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">
                            <i class="fas fa-code mr-2"></i>Value
                        </h4>
                        @if($setting->type === 'boolean')
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $setting->value ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                    <i class="fas {{ $setting->value ? 'fa-check' : 'fa-times' }} mr-2"></i>
                                    {{ $setting->value ? 'True' : 'False' }}
                                </span>
                            </div>
                        @elseif($setting->type === 'json')
                            <pre class="text-xs text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 p-4 rounded overflow-x-auto">{{ json_encode($setting->value, JSON_PRETTY_PRINT) }}</pre>
                        @else
                            <div class="bg-white dark:bg-gray-800 p-4 rounded border">
                                <code class="text-sm text-gray-900 dark:text-gray-100">{{ $setting->value }}</code>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('superadmin.system.settings.index') }}"
                               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-arrow-left mr-2"></i>Back to Settings
                            </a>
                            <a href="{{ route('superadmin.system.settings.edit', $setting) }}"
                               class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-edit mr-2"></i>Edit Setting
                            </a>
                            <button onclick="deleteSetting({{ $setting->id }}, '{{ $setting->key }}')"
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-trash mr-2"></i>Delete Setting
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteSetting(settingId, settingKey) {
            if (confirm(`Are you sure you want to delete the setting "${settingKey}"? This action cannot be undone.`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/superadmin/system/settings/${settingId}`;

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';

                const csrfField = document.createElement('input');
                csrfField.type = 'hidden';
                csrfField.name = '_token';
                csrfField.value = '{{ csrf_token() }}';

                form.appendChild(methodField);
                form.appendChild(csrfField);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-superadmin-layout>