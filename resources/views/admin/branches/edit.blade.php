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

                    <!-- Interactive Map -->
                    <div class="md:col-span-2">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-md font-medium text-gray-900 dark:text-white">Set Location on Map</h4>
                            <div class="flex items-center space-x-2">
                                <button type="button" id="get-current-location" class="inline-flex items-center px-3 py-1 text-sm bg-green-600 hover:bg-green-700 text-white rounded-md transition-colors duration-200">
                                    <i class="fa-solid fa-crosshairs mr-1"></i>Get My Location
                                </button>
                                <button type="button" id="clear-location" class="inline-flex items-center px-3 py-1 text-sm bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-200">
                                    <i class="fa-solid fa-times mr-1"></i>Clear
                                </button>
                            </div>
                        </div>

                        <div class="relative">
                            <div id="map-container" class="w-full h-80 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden shadow-inner border-2 border-dashed border-gray-300 dark:border-gray-600"></div>

                            <!-- Map Instructions -->
                            <div class="absolute top-3 left-3 bg-white dark:bg-gray-800 px-3 py-2 rounded-lg shadow-lg max-w-xs">
                                <div class="flex items-start space-x-2">
                                    <i class="fa-solid fa-info-circle text-blue-500 mt-0.5"></i>
                                    <div class="text-xs">
                                        <p class="font-medium text-gray-900 dark:text-white">Click on the map to set location</p>
                                        <p class="text-gray-600 dark:text-gray-400">Or enter coordinates manually above</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Coordinate Display -->
                            <div id="coordinate-display" class="absolute bottom-3 left-3 bg-white dark:bg-gray-800 px-3 py-2 rounded-lg shadow-lg hidden">
                                <div class="text-xs">
                                    <div class="font-medium text-gray-900 dark:text-white">Selected Location:</div>
                                    <div id="selected-coords" class="text-gray-600 dark:text-gray-400 font-mono"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Map Statistics -->
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg">
                                <div class="flex items-center space-x-2">
                                    <i class="fa-solid fa-map-pin text-blue-600 dark:text-blue-400"></i>
                                    <div class="flex-1">
                                        <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">Latitude</p>
                                        <input type="text" id="lat-display" readonly
                                               class="w-full text-sm font-semibold text-blue-900 dark:text-blue-100 bg-transparent border-none p-0 focus:ring-0"
                                               value="{{ $branch->latitude ? number_format($branch->latitude, 6) : 'Not set' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg">
                                <div class="flex items-center space-x-2">
                                    <i class="fa-solid fa-map-pin text-green-600 dark:text-green-400"></i>
                                    <div class="flex-1">
                                        <p class="text-xs text-green-600 dark:text-green-400 font-medium">Longitude</p>
                                        <input type="text" id="lng-display" readonly
                                               class="w-full text-sm font-semibold text-green-900 dark:text-green-100 bg-transparent border-none p-0 focus:ring-0"
                                               value="{{ $branch->longitude ? number_format($branch->longitude, 6) : 'Not set' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/20 p-3 rounded-lg">
                                <div class="flex items-center space-x-2">
                                    <i class="fa-solid fa-circle-dot text-purple-600 dark:text-purple-400"></i>
                                    <div class="flex-1">
                                        <p class="text-xs text-purple-600 dark:text-purple-400 font-medium">Radius</p>
                                        <p class="text-sm font-semibold text-purple-900 dark:text-purple-100">{{ $branch->radius ?? 100 }}m</p>
                                    </div>
                                </div>
                            </div>
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

    <!-- OpenStreetMap Integration -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let map;
            let marker;
            let radiusCircle;
            let isMapInitialized = false;

            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const radiusInput = document.getElementById('radius');
            const latDisplay = document.getElementById('lat-display');
            const lngDisplay = document.getElementById('lng-display');
            const coordinateDisplay = document.getElementById('coordinate-display');
            const selectedCoords = document.getElementById('selected-coords');

            // Default coordinates (Jakarta, Indonesia)
            const defaultLat = {{ $branch->latitude ?? -6.208763 }};
            const defaultLng = {{ $branch->longitude ?? 106.845599 }};
            const defaultRadius = {{ $branch->radius ?? 100 }};

            function initializeMap() {
                if (isMapInitialized) return;

                // Initialize map
                map = L.map('map-container', {
                    center: [defaultLat, defaultLng],
                    zoom: 15,
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

                // Add scale control
                L.control.scale({
                    position: 'bottomleft',
                    metric: true,
                    imperial: false
                }).addTo(map);

                // Add click event to map
                map.on('click', function(e) {
                    setLocation(e.latlng.lat, e.latlng.lng);
                });

                // Initialize with existing coordinates if available
                if (latInput.value && lngInput.value) {
                    setLocation(parseFloat(latInput.value), parseFloat(lngInput.value));
                }

                isMapInitialized = true;
            }

            function setLocation(lat, lng) {
                // Update input fields
                latInput.value = lat.toFixed(6);
                lngInput.value = lng.toFixed(6);

                // Update display
                latDisplay.value = lat.toFixed(6);
                lngDisplay.value = lng.toFixed(6);

                // Show coordinate display
                selectedCoords.textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                coordinateDisplay.classList.remove('hidden');

                // Remove existing marker and circle
                if (marker) map.removeLayer(marker);
                if (radiusCircle) map.removeLayer(radiusCircle);

                // Add new marker
                const branchIcon = L.divIcon({
                    html: `
                        <div class="flex items-center justify-center w-12 h-12 bg-blue-500 rounded-full shadow-lg border-4 border-white animate-pulse">
                            <i class="fa-solid fa-building text-white text-lg"></i>
                        </div>
                    `,
                    className: 'custom-branch-marker',
                    iconSize: [48, 48],
                    iconAnchor: [24, 24]
                });

                marker = L.marker([lat, lng], {
                    icon: branchIcon,
                    draggable: true
                }).addTo(map);

                // Add drag event to marker
                marker.on('dragend', function(e) {
                    const newLatLng = e.target.getLatLng();
                    setLocation(newLatLng.lat, newLatLng.lng);
                });

                // Add radius circle
                const currentRadius = parseInt(radiusInput.value) || defaultRadius;
                radiusCircle = L.circle([lat, lng], {
                    color: '#ef4444',
                    fillColor: '#fca5a5',
                    fillOpacity: 0.2,
                    weight: 2,
                    radius: currentRadius
                }).addTo(map);

                // Center map on location
                map.setView([lat, lng], 16);

                // Add popup to marker
                marker.bindPopup(`
                    <div class="text-center">
                        <h4 class="font-semibold text-gray-900">{{ $branch->name }}</h4>
                        <p class="text-sm text-gray-600">Click and drag to adjust location</p>
                        <div class="mt-2 text-xs text-gray-500">
                            <div>Lat: ${lat.toFixed(6)}</div>
                            <div>Lng: ${lng.toFixed(6)}</div>
                        </div>
                    </div>
                `).openPopup();
            }

            function updateRadius() {
                if (radiusCircle && latInput.value && lngInput.value) {
                    const lat = parseFloat(latInput.value);
                    const lng = parseFloat(lngInput.value);
                    const radius = parseInt(radiusInput.value) || defaultRadius;

                    map.removeLayer(radiusCircle);
                    radiusCircle = L.circle([lat, lng], {
                        color: '#ef4444',
                        fillColor: '#fca5a5',
                        fillOpacity: 0.2,
                        weight: 2,
                        radius: radius
                    }).addTo(map);
                }
            }

            // Event listeners
            latInput.addEventListener('input', function() {
                if (this.value && lngInput.value) {
                    setLocation(parseFloat(this.value), parseFloat(lngInput.value));
                }
            });

            lngInput.addEventListener('input', function() {
                if (latInput.value && this.value) {
                    setLocation(parseFloat(latInput.value), parseFloat(this.value));
                }
            });

            radiusInput.addEventListener('input', updateRadius);

            // Get current location button
            document.getElementById('get-current-location').addEventListener('click', function() {
                if (navigator.geolocation) {
                    this.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i>Getting Location...';
                    this.disabled = true;

                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            setLocation(position.coords.latitude, position.coords.longitude);
                            document.getElementById('get-current-location').innerHTML = '<i class="fa-solid fa-crosshairs mr-1"></i>Get My Location';
                            document.getElementById('get-current-location').disabled = false;
                        },
                        function(error) {
                            alert('Unable to get your location. Please enter coordinates manually.');
                            document.getElementById('get-current-location').innerHTML = '<i class="fa-solid fa-crosshairs mr-1"></i>Get My Location';
                            document.getElementById('get-current-location').disabled = false;
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 300000
                        }
                    );
                } else {
                    alert('Geolocation is not supported by this browser.');
                }
            });

            // Clear location button
            document.getElementById('clear-location').addEventListener('click', function() {
                latInput.value = '';
                lngInput.value = '';
                latDisplay.value = 'Not set';
                lngDisplay.value = 'Not set';
                coordinateDisplay.classList.add('hidden');

                if (marker) map.removeLayer(marker);
                if (radiusCircle) map.removeLayer(radiusCircle);

                marker = null;
                radiusCircle = null;
            });

            // Initialize map when page loads
            initializeMap();

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
                .animate-pulse {
                    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
                }
                @keyframes pulse {
                    0%, 100% { opacity: 1; }
                    50% { opacity: .5; }
                }
            `;
            document.head.appendChild(style);
        });
    </script>
</x-admin-layout>