<x-admin-layout>
    <div class="py-2">
        <x-page-header
            title="Branch Details"
            subtitle="View detailed information about {{ $branch->name }}"
        />

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Branch Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Branch Information</h3>
                        <a href="{{ route('admin.branches.edit', $branch) }}"
                           class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors duration-200">
                            <i class="fa-solid fa-edit mr-2"></i>Edit
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Branch Name</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $branch->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($branch->is_active) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                <i class="fa-solid @if($branch->is_active) fa-check-circle @else fa-times-circle @endif mr-1"></i>
                                {{ $branch->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Timezone</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $branch->timezone }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Attendance Radius</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $branch->radius }} meters</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Address</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $branch->address }}</p>
                        </div>

                        @if($branch->latitude && $branch->longitude)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Latitude</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ number_format($branch->latitude, 6) }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Longitude</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ number_format($branch->longitude, 6) }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Location Map -->
                @if($branch->latitude && $branch->longitude)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Location Map</h3>
                            <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                                <i class="fa-solid fa-map-marker-alt"></i>
                                <span>{{ number_format($branch->latitude, 6) }}, {{ number_format($branch->longitude, 6) }}</span>
                            </div>
                        </div>

                        <!-- Map Container -->
                        <div class="relative">
                            <div id="map-container" class="w-full h-80 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden shadow-inner"></div>

                            <!-- Map Controls -->
                            <div class="absolute top-3 right-3 flex flex-col space-y-2">
                                <button id="fullscreen-btn" class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 p-2 rounded-lg shadow-lg transition-colors duration-200" title="Fullscreen">
                                    <i class="fa-solid fa-expand"></i>
                                </button>
                                <button id="locate-btn" class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 p-2 rounded-lg shadow-lg transition-colors duration-200" title="Center on location">
                                    <i class="fa-solid fa-crosshairs"></i>
                                </button>
                            </div>

                            <!-- Map Info Overlay -->
                            <div class="absolute bottom-3 left-3 bg-white dark:bg-gray-800 px-3 py-2 rounded-lg shadow-lg">
                                <div class="flex items-center space-x-2 text-sm">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
                                    <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $branch->name }}</span>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Attendance Radius: {{ $branch->radius }}m
                                </div>
                            </div>
                        </div>

                        <!-- Map Statistics -->
                        <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg">
                                <div class="flex items-center space-x-2">
                                    <i class="fa-solid fa-map-pin text-blue-600 dark:text-blue-400"></i>
                                    <div>
                                        <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">Latitude</p>
                                        <p class="text-sm font-semibold text-blue-900 dark:text-blue-100">{{ number_format($branch->latitude, 4) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg">
                                <div class="flex items-center space-x-2">
                                    <i class="fa-solid fa-map-pin text-green-600 dark:text-green-400"></i>
                                    <div>
                                        <p class="text-xs text-green-600 dark:text-green-400 font-medium">Longitude</p>
                                        <p class="text-sm font-semibold text-green-900 dark:text-green-100">{{ number_format($branch->longitude, 4) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/20 p-3 rounded-lg">
                                <div class="flex items-center space-x-2">
                                    <i class="fa-solid fa-circle-dot text-purple-600 dark:text-purple-400"></i>
                                    <div>
                                        <p class="text-xs text-purple-600 dark:text-purple-400 font-medium">Radius</p>
                                        <p class="text-sm font-semibold text-purple-900 dark:text-purple-100">{{ $branch->radius }}m</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-orange-50 dark:bg-orange-900/20 p-3 rounded-lg">
                                <div class="flex items-center space-x-2">
                                    <i class="fa-solid fa-clock text-orange-600 dark:text-orange-400"></i>
                                    <div>
                                        <p class="text-xs text-orange-600 dark:text-orange-400 font-medium">Timezone</p>
                                        <p class="text-sm font-semibold text-orange-900 dark:text-orange-100">{{ $branch->timezone }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Recent Attendance -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Recent Attendance Records</h3>

                    @if($branch->attendances->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Employee</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Check In</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Check Out</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($branch->attendances->take(10) as $attendance)
                                        <tr>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $attendance->user->name }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $attendance->attendance_date->format('d/m/Y') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                    @if($attendance->status === 'present') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @elseif($attendance->status === 'late') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @elseif($attendance->status === 'absent') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                                    {{ ucfirst($attendance->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : '-' }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">No attendance records found for this branch.</p>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Quick Actions</h3>

                    <div class="space-y-3">
                        <a href="{{ route('admin.branches.edit', $branch) }}"
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors duration-200">
                            <i class="fa-solid fa-edit mr-2"></i>Edit Branch
                        </a>

                        <form method="POST" action="{{ route('admin.branches.destroy', $branch) }}"
                              onsubmit="return confirm('Are you sure you want to delete this branch? This action cannot be undone.')"
                              class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transition-colors duration-200">
                                <i class="fa-solid fa-trash mr-2"></i>Delete Branch
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Branch Statistics</h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Total Employees</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $branch->employees->count() }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Total Attendance Records</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $branch->attendances->count() }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Present Today</span>
                            <span class="text-sm font-medium text-green-600">{{ $branch->attendances->where('attendance_date', today())->where('status', 'present')->count() }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Late Today</span>
                            <span class="text-sm font-medium text-yellow-600">{{ $branch->attendances->where('attendance_date', today())->where('status', 'late')->count() }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Absent Today</span>
                            <span class="text-sm font-medium text-red-600">{{ $branch->attendances->where('attendance_date', today())->where('status', 'absent')->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Branch Status -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Branch Status</h3>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Current Status</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($branch->is_active) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                {{ $branch->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Attendance Radius</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $branch->radius }}m</span>
                        </div>

                        @if($branch->latitude && $branch->longitude)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">GPS Location</span>
                                <span class="text-sm text-gray-900 dark:text-white">Set</span>
                            </div>
                        @else
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">GPS Location</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Not Set</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('admin.branches.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back to Branches
            </a>
        </div>
    </div>

    <!-- OpenStreetMap Integration -->
    @if($branch->latitude && $branch->longitude)
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize map
            const map = L.map('map-container', {
                center: [{{ $branch->latitude }}, {{ $branch->longitude }}],
                zoom: 16,
                zoomControl: true,
                scrollWheelZoom: true,
                doubleClickZoom: true,
                boxZoom: true,
                keyboard: true,
                dragging: true,
                touchZoom: true
            });

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19,
                minZoom: 3
            }).addTo(map);

            // Add marker for branch location
            const branchIcon = L.divIcon({
                html: `
                    <div class="flex items-center justify-center w-12 h-12 bg-blue-500 rounded-full shadow-lg border-4 border-white">
                        <i class="fa-solid fa-building text-white text-lg"></i>
                    </div>
                `,
                className: 'custom-branch-marker',
                iconSize: [48, 48],
                iconAnchor: [24, 24]
            });

            const marker = L.marker([{{ $branch->latitude }}, {{ $branch->longitude }}], {
                icon: branchIcon
            }).addTo(map);

            // Add attendance radius circle
            const radiusCircle = L.circle([{{ $branch->latitude }}, {{ $branch->longitude }}], {
                color: '#ef4444',
                fillColor: '#fca5a5',
                fillOpacity: 0.2,
                weight: 2,
                radius: {{ $branch->radius }}
            }).addTo(map);

            // Add popup to marker
            marker.bindPopup(`
                <div class="text-center">
                    <h4 class="font-semibold text-gray-900">{{ $branch->name }}</h4>
                    <p class="text-sm text-gray-600">{{ $branch->address }}</p>
                    <div class="mt-2 text-xs text-gray-500">
                        <div>Lat: {{ number_format($branch->latitude, 6) }}</div>
                        <div>Lng: {{ number_format($branch->longitude, 6) }}</div>
                        <div>Radius: {{ $branch->radius }}m</div>
                    </div>
                </div>
            `);

            // Add radius circle popup
            radiusCircle.bindPopup(`
                <div class="text-center">
                    <h4 class="font-semibold text-red-600">Attendance Zone</h4>
                    <p class="text-sm text-gray-600">Radius: {{ $branch->radius }} meters</p>
                    <p class="text-xs text-gray-500">Employees must be within this area to check attendance</p>
                </div>
            `);

            // Add scale control
            L.control.scale({
                position: 'bottomleft',
                metric: true,
                imperial: false
            }).addTo(map);

            // Fullscreen functionality
            document.getElementById('fullscreen-btn').addEventListener('click', function() {
                const mapContainer = document.getElementById('map-container');
                if (mapContainer.requestFullscreen) {
                    mapContainer.requestFullscreen();
                } else if (mapContainer.webkitRequestFullscreen) {
                    mapContainer.webkitRequestFullscreen();
                } else if (mapContainer.msRequestFullscreen) {
                    mapContainer.msRequestFullscreen();
                }
                map.invalidateSize();
            });

            // Center on location functionality
            document.getElementById('locate-btn').addEventListener('click', function() {
                map.setView([{{ $branch->latitude }}, {{ $branch->longitude }}], 18);
                marker.openPopup();
            });

            // Handle fullscreen changes
            document.addEventListener('fullscreenchange', function() {
                setTimeout(() => {
                    map.invalidateSize();
                }, 100);
            });

            document.addEventListener('webkitfullscreenchange', function() {
                setTimeout(() => {
                    map.invalidateSize();
                }, 100);
            });

            document.addEventListener('mozfullscreenchange', function() {
                setTimeout(() => {
                    map.invalidateSize();
                }, 100);
            });

            document.addEventListener('MSFullscreenChange', function() {
                setTimeout(() => {
                    map.invalidateSize();
                }, 100);
            });

            // Add custom CSS for better map styling
            const style = document.createElement('style');
            style.textContent = `
                .leaflet-control-container .leaflet-routing-container-hide {
                    display: none;
                }
                .custom-branch-marker {
                    background: transparent !important;
                    border: none !important;
                }
                .leaflet-popup-content-wrapper {
                    border-radius: 8px;
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                }
                .leaflet-popup-tip {
                    background-color: white;
                }
                .leaflet-control-scale {
                    border: 1px solid rgba(255, 255, 255, 0.8);
                    border-radius: 4px;
                    background: rgba(255, 255, 255, 0.8);
                }
            `;
            document.head.appendChild(style);
        });
    </script>
    @endif
</x-admin-layout>