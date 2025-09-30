<x-superadmin-layout>
<div class="space-y-6">
    <!-- Bulk Notification Form -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Send Bulk Notifications</h2>
                <a href="{{ route('superadmin.notifications.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Notifications
                </a>
            </div>
        </div>

    <form action="{{ route('superadmin.notifications.send-bulk') }}" method="POST" class="p-6 space-y-6">
        @csrf

        <!-- Notification Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                <input type="text" name="title" id="title" required
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Enter notification title">
            </div>

            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                <select name="type" id="type" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="info">Info</option>
                    <option value="success">Success</option>
                    <option value="warning">Warning</option>
                    <option value="error">Error</option>
                    <option value="system">System</option>
                </select>
            </div>
        </div>

        <div>
            <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Message</label>
            <textarea name="message" id="message" rows="4" required
                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                      placeholder="Enter notification message"></textarea>
        </div>

        <!-- Target Selection -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Target Audience</label>

            <div class="space-y-4">
                <!-- Target Type -->
                <div>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="target_type" value="all_admins" class="text-indigo-600 focus:ring-indigo-500" checked>
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">All Admins</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="target_type" value="specific_admins" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Specific Admins</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="target_type" value="all_employees" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">All Employees</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="target_type" value="specific_employees" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Specific Employees</span>
                        </label>
                    </div>
                </div>

                <!-- Admin Selection -->
                <div id="admin-selection" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Admins</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 max-h-60 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md p-3">
                        @php
                            $admins = \App\Models\Core\User::where('role', 'admin')->get();
                        @endphp
                        @foreach($admins as $admin)
                            <label class="flex items-center">
                                <input type="checkbox" name="admin_ids[]" value="{{ $admin->id }}" class="text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $admin->name }} ({{ $admin->instansi ? $admin->instansi->nama_instansi : 'N/A' }})</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Employee Selection -->
                <div id="employee-selection" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Employees</label>
                    <div class="space-y-2">
                        <input type="text" id="employee-search" placeholder="Search employees..."
                               class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <div id="employee-list" class="max-h-60 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md p-3">
                            <!-- Employee list will be populated via AJAX -->
                            <p class="text-sm text-gray-500 dark:text-gray-400">Select "Specific Employees" to load employee list</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview -->
        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 bg-gray-50 dark:bg-gray-700">
            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Preview</h3>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0 pt-0.5">
                        <div class="h-8 w-8 rounded-full flex items-center justify-center bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                            <i class="fas fa-info text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white" id="preview-title">Notification Title</p>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-0.5" id="preview-message">Notification message will appear here</p>
                        <div class="mt-1 flex items-center text-xs text-gray-500 dark:text-gray-400">
                            <span>Just now</span>
                            <span class="mx-1">•</span>
                            <span id="preview-type">info</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-paper-plane mr-2"></i> Send Notifications
            </button>
        </div>
    </form>
</div>

<!-- Bulk Notification History -->
<div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Bulk Notification History</h3>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Recent bulk notifications sent to multiple recipients</p>
    </div>

    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        @forelse($bulkHistory ?? [] as $bulk)
            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                <div class="flex items-start">
                    <div class="flex-shrink-0 pt-0.5">
                        <div class="h-10 w-10 rounded-full flex items-center justify-center
                            @if($bulk->type === 'success') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                            @elseif($bulk->type === 'error') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                            @elseif($bulk->type === 'warning') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                            @elseif($bulk->type === 'system') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300
                            @else bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                            @endif">
                            <i class="fas
                                @if($bulk->type === 'success') fa-check-circle
                                @elseif($bulk->type === 'error') fa-times-circle
                                @elseif($bulk->type === 'warning') fa-exclamation-triangle
                                @elseif($bulk->type === 'system') fa-cog
                                @else fa-info-circle
                                @endif"></i>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $bulk->title }}
                            </p>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($bulk->type === 'success') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                    @elseif($bulk->type === 'error') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                    @elseif($bulk->type === 'warning') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                    @elseif($bulk->type === 'system') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300
                                    @else bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                    @endif">
                                    {{ ucfirst($bulk->type) }}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $bulk->total_sent }} sent • {{ $bulk->read_count }} read
                                </span>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                            {{ $bulk->message }}
                        </p>
                        <div class="mt-2 flex items-center justify-between">
                            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                <span>Bulk notification</span>
                                <span class="mx-2">•</span>
                                <time datetime="{{ $bulk->created_at }}" title="{{ \Carbon\Carbon::parse($bulk->created_at)->format('M j, Y g:i A') }}">
                                    {{ \Carbon\Carbon::parse($bulk->created_at)->diffForHumans() }}
                                </time>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ round(($bulk->read_count / $bulk->total_sent) * 100) }}% read rate
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                <i class="fas fa-history text-4xl mb-3 opacity-30"></i>
                <p class="text-lg font-medium">No bulk notifications sent yet</p>
                <p class="text-sm">Send your first bulk notification to see history here.</p>
            </div>
        @endforelse
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const targetTypeRadios = document.querySelectorAll('input[name="target_type"]');
    const adminSelection = document.getElementById('admin-selection');
    const employeeSelection = document.getElementById('employee-selection');
    const employeeSearch = document.getElementById('employee-search');
    const employeeList = document.getElementById('employee-list');

    // Handle target type changes
    targetTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            adminSelection.classList.add('hidden');
            employeeSelection.classList.add('hidden');

            if (this.value === 'specific_admins') {
                adminSelection.classList.remove('hidden');
            } else if (this.value === 'specific_employees') {
                employeeSelection.classList.remove('hidden');
                loadEmployees();
            }
        });
    });

    // Load employees for selection
    function loadEmployees() {
        fetch('/superadmin/api/employees')
            .then(response => response.json())
            .then(data => {
                employeeList.innerHTML = '';
                data.employees.forEach(employee => {
                    const label = document.createElement('label');
                    label.className = 'flex items-center py-1';
                    label.innerHTML = `
                        <input type="checkbox" name="user_ids[]" value="${employee.id}" class="text-indigo-600 focus:ring-indigo-500 mr-2">
                        <span class="text-sm text-gray-700 dark:text-gray-300">${employee.name} (${employee.instansi_name})</span>
                    `;
                    employeeList.appendChild(label);
                });
            });
    }

    // Employee search
    employeeSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const labels = employeeList.querySelectorAll('label');
        labels.forEach(label => {
            const text = label.textContent.toLowerCase();
            label.style.display = text.includes(searchTerm) ? 'flex' : 'none';
        });
    });

    // Live preview
    const titleInput = document.getElementById('title');
    const messageInput = document.getElementById('message');
    const typeSelect = document.getElementById('type');
    const previewTitle = document.getElementById('preview-title');
    const previewMessage = document.getElementById('preview-message');
    const previewType = document.getElementById('preview-type');

    function updatePreview() {
        previewTitle.textContent = titleInput.value || 'Notification Title';
        previewMessage.textContent = messageInput.value || 'Notification message will appear here';
        previewType.textContent = typeSelect.value;
    }

    titleInput.addEventListener('input', updatePreview);
    messageInput.addEventListener('input', updatePreview);
    typeSelect.addEventListener('change', updatePreview);
});
</script>
</x-superadmin-layout>