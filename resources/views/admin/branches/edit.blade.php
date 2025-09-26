<x-admin-layout>
    <div class="py-2">
        <x-page-header
            title="Edit Branch"
            subtitle="Update branch information for {{ $branch->name }}"
        />

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form method="POST" action="{{ route('admin.branches.update', $branch) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Basic Information</h3>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Branch Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $branch->name) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="timezone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Timezone *</label>
                        <select id="timezone" name="timezone"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('timezone') border-red-500 @enderror"
                                required>
                            <option value="">Select Timezone</option>
                            <option value="Asia/Jakarta" {{ old('timezone', $branch->timezone) == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (WIB)</option>
                            <option value="Asia/Makassar" {{ old('timezone', $branch->timezone) == 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar (WITA)</option>
                            <option value="Asia/Jayapura" {{ old('timezone', $branch->timezone) == 'Asia/Jayapura' ? 'selected' : '' }}>Asia/Jayapura (WIT)</option>
                            <option value="UTC" {{ old('timezone', $branch->timezone) == 'UTC' ? 'selected' : '' }}>UTC</option>
                        </select>
                        @error('timezone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address Information -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Address Information</h3>
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address *</label>
                        <textarea id="address" name="address" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('address') border-red-500 @enderror"
                                  required>{{ old('address', $branch->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location Information -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Location Settings</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Set the GPS coordinates and radius for attendance tracking at this branch.
                        </p>
                    </div>

                    <div>
                        <label for="latitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Latitude</label>
                        <input type="number" id="latitude" name="latitude" value="{{ old('latitude', $branch->latitude) }}" step="0.000001" min="-90" max="90"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('latitude') border-red-500 @enderror"
                               placeholder="-6.208763">
                        @error('latitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="longitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Longitude</label>
                        <input type="number" id="longitude" name="longitude" value="{{ old('longitude', $branch->longitude) }}" step="0.000001" min="-180" max="180"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('longitude') border-red-500 @enderror"
                               placeholder="106.845599">
                        @error('longitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="radius" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Attendance Radius (meters) *</label>
                        <input type="number" id="radius" name="radius" value="{{ old('radius', $branch->radius) }}" min="10" max="1000"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('radius') border-red-500 @enderror"
                               required>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Maximum distance allowed for attendance check-in/check-out</p>
                        @error('radius')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $branch->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-white">
                            Active Branch
                        </label>
                    </div>

                    <!-- Map Preview -->
                    <div class="md:col-span-2">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-2">Location Preview</h4>
                        <div id="map-container" class="w-full h-64 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                            @if($branch->latitude && $branch->longitude)
                                <div class="text-center text-gray-500 dark:text-gray-400">
                                    <i class="fa-solid fa-map-marked-alt text-3xl mb-2"></i>
                                    <p>Current location: {{ number_format($branch->latitude, 6) }}, {{ number_format($branch->longitude, 6) }}</p>
                                    <p class="text-xs">Update coordinates above to change location</p>
                                </div>
                            @else
                                <div class="text-center text-gray-500 dark:text-gray-400">
                                    <i class="fa-solid fa-map-marked-alt text-3xl mb-2"></i>
                                    <p>Enter coordinates above to preview location</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.branches.show', $branch) }}"
                       class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                        <i class="fa-solid fa-save mr-2"></i>Update Branch
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/admin/branches.js') }}"></script>
</x-admin-layout>