<x-admin-layout>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header
                title="Policy Templates"
                subtitle="Manage reusable employee policy templates"
                :show-period-filter="false"
            />

            <div class="flex justify-end mb-6 space-x-3">
                <a href="{{ route('admin.employee-policy-templates.apply') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                    <i class="fa-solid fa-users-gear mr-2"></i>Apply to Employees
                </a>
                <a href="{{ route('admin.employee-policy-templates.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                    <i class="fa-solid fa-plus mr-2"></i>Create Template
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($templates->isEmpty())
                        <div class="text-center py-12">
                            <i class="fa-solid fa-file-lines text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                            <p class="text-gray-500 dark:text-gray-400 text-lg mb-4">No templates found</p>
                            <a href="{{ route('admin.employee-policy-templates.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200">
                                <i class="fa-solid fa-plus mr-2"></i>Create Your First Template
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($templates as $template)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6 hover:shadow-lg transition-shadow duration-200 {{ $template->is_default ? 'ring-2 ring-blue-500' : '' }}">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                                {{ $template->name }}
                                                @if($template->is_default)
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        Default
                                                    </span>
                                                @endif
                                            </h3>
                                            @if($template->description)
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($template->description, 80) }}</p>
                                            @endif
                                        </div>
                                        <span class="ml-2 inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $template->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                            {{ $template->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>

                                    <div class="space-y-2 mb-4">
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <i class="fa-solid fa-calendar-days w-5"></i>
                                            <span class="ml-2">{{ $template->formatted_work_days }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <i class="fa-solid fa-clock w-5"></i>
                                            <span class="ml-2">{{ $template->formatted_schedule }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <i class="fa-solid fa-hourglass-half w-5"></i>
                                            <span class="ml-2">{{ $template->work_hours_per_day ?? 'N/A' }} hours/day</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <a href="{{ route('admin.employee-policy-templates.show', $template) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">
                                            <i class="fa-solid fa-eye mr-1"></i>View
                                        </a>
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('admin.employee-policy-templates.edit', $template) }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                                                <i class="fa-solid fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.employee-policy-templates.destroy', $template) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this template?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
