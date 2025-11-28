<x-admin-layout>
    <div class="py-2">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-page-header
                title="Apply Policy Template"
                subtitle="Apply a template to multiple employees at once"
                :show-period-filter="false"
            />

            <div class="flex justify-end mb-6">
                <a href="{{ route('admin.employee-policy-templates.index') }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                    <i class="fa-solid fa-arrow-left mr-2"></i>Back to Templates
                </a>
            </div>

            <form method="POST" action="{{ route('admin.employee-policies.apply-template') }}">
                @csrf

                <x-section-card title="Select Template">
                    <div>
                        <label for="template_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Policy Template <span class="text-red-500">*</span>
                        </label>
                        <select name="template_id" id="template_id" required
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('template_id') border-red-300 dark:border-red-600 @enderror">
                            <option value="">Select a template</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}" {{ old('template_id') == $template->id ? 'selected' : '' }}
                                        data-description="{{ $template->description }}"
                                        data-schedule="{{ $template->formatted_schedule }}"
                                        data-days="{{ $template->formatted_work_days }}">
                                    {{ $template->name }}
                                    @if($template->is_default)
                                        (Default)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('template_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror

                        <!-- Template Preview -->
                        <div id="template-preview" class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hidden">
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Template Details:</h4>
                            <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                <p id="preview-description"></p>
                                <p><strong>Work Days:</strong> <span id="preview-days"></span></p>
                                <p><strong>Schedule:</strong> <span id="preview-schedule"></span></p>
                            </div>
                        </div>
                    </div>
                </x-section-card>

                <x-section-card title="Select Employees">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Employees <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center space-x-2">
                                <button type="button" id="select-all" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    Select All
                                </button>
                                <span class="text-gray-400">|</span>
                                <button type="button" id="deselect-all" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    Deselect All
                                </button>
                            </div>
                        </div>

                        @error('employee_ids')
                            <p class="mb-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror

                        <div class="max-h-96 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md p-3 space-y-2">
                            @forelse($employees as $employee)
                                <label class="flex items-center p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg cursor-pointer transition-colors">
                                    <input type="checkbox" name="employee_ids[]" value="{{ $employee->id }}"
                                           {{ in_array($employee->id, old('employee_ids', [])) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $employee->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $employee->email }}</p>
                                    </div>
                                </label>
                            @empty
                                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    <i class="fa-solid fa-users-slash text-4xl mb-2"></i>
                                    <p>No employees available</p>
                                    <p class="text-sm">All employees already have active policies</p>
                                </div>
                            @endforelse
                        </div>

                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            <i class="fa-solid fa-info-circle mr-1"></i>
                            Only employees without active policies are shown
                        </p>
                    </div>
                </x-section-card>

                <div class="mt-6 flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.employee-policy-templates.index') }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        <i class="fa-solid fa-times mr-2"></i>Cancel
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        <i class="fa-solid fa-check mr-2"></i>Apply Template
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Template preview
        document.getElementById('template_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const preview = document.getElementById('template-preview');
            
            if (this.value) {
                document.getElementById('preview-description').textContent = selectedOption.dataset.description || 'No description';
                document.getElementById('preview-days').textContent = selectedOption.dataset.days;
                document.getElementById('preview-schedule').textContent = selectedOption.dataset.schedule;
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }
        });

        // Select/Deselect all
        document.getElementById('select-all').addEventListener('click', function() {
            document.querySelectorAll('input[name="employee_ids[]"]').forEach(checkbox => {
                checkbox.checked = true;
            });
        });

        document.getElementById('deselect-all').addEventListener('click', function() {
            document.querySelectorAll('input[name="employee_ids[]"]').forEach(checkbox => {
                checkbox.checked = false;
            });
        });
    </script>
</x-admin-layout>
