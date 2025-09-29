<x-superadmin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('User Log Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('superadmin.system.user-logs.index') }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Logs
                </a>
                <button onclick="deleteLog({{ $userLog->id }})"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-trash mr-2"></i>Delete Log
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Log Header -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    Log #{{ $userLog->id }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Created {{ $userLog->created_at->format('F d, Y \a\t H:i:s') }}
                                </p>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($userLog->action === 'login') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                @elseif($userLog->action === 'logout') bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300
                                @elseif(in_array($userLog->action, ['create', 'store'])) bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                @elseif(in_array($userLog->action, ['update', 'edit'])) bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                @elseif(in_array($userLog->action, ['delete', 'destroy'])) bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                @else bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                @endif">
                                {{ ucfirst($userLog->action) }}
                            </span>
                        </div>
                    </div>

                    <!-- Log Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- User Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">
                                <i class="fas fa-user mr-2"></i>User Information
                            </h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Name:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $userLog->user->name ?? 'Unknown User' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Email:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $userLog->user->email ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Role:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $userLog->user->role ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">User ID:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $userLog->user_id }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Technical Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">
                                <i class="fas fa-cogs mr-2"></i>Technical Information
                            </h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">IP Address:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $userLog->ip_address ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">User Agent:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100 max-w-xs truncate" title="{{ $userLog->user_agent }}">
                                        {{ $userLog->user_agent ? Str::limit($userLog->user_agent, 30) : 'N/A' }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Log ID:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $userLog->id }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Timestamp:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $userLog->created_at->format('H:i:s') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Model Information -->
                    @if($userLog->model_type && $userLog->model_id)
                        <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">
                                <i class="fas fa-database mr-2"></i>Affected Resource
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Model Type:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ class_basename($userLog->model_type) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Model ID:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $userLog->model_id }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Description -->
                    <div class="mt-6 bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">
                            <i class="fas fa-file-alt mr-2"></i>Description
                        </h4>
                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                            {{ $userLog->description }}
                        </p>
                    </div>

                    <!-- Old/New Values (for updates) -->
                    @if($userLog->old_values || $userLog->new_values)
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">
                                <i class="fas fa-exchange-alt mr-2"></i>Changes Made
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if($userLog->old_values)
                                    <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
                                        <h5 class="text-sm font-medium text-red-800 dark:text-red-300 mb-2">
                                            <i class="fas fa-minus-circle mr-2"></i>Before
                                        </h5>
                                        <pre class="text-xs text-red-700 dark:text-red-400 bg-red-100 dark:bg-red-900/30 p-3 rounded overflow-x-auto">{{ json_encode($userLog->old_values, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                @endif

                                @if($userLog->new_values)
                                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                                        <h5 class="text-sm font-medium text-green-800 dark:text-green-300 mb-2">
                                            <i class="fas fa-plus-circle mr-2"></i>After
                                        </h5>
                                        <pre class="text-xs text-green-700 dark:text-green-400 bg-green-100 dark:bg-green-900/30 p-3 rounded overflow-x-auto">{{ json_encode($userLog->new_values, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('superadmin.system.user-logs.index') }}"
                               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-arrow-left mr-2"></i>Back to Logs
                            </a>
                            <button onclick="deleteLog({{ $userLog->id }})"
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-trash mr-2"></i>Delete Log
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteLog(logId) {
            if (confirm('Are you sure you want to delete this log? This action cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/superadmin/system/user-logs/${logId}`;

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