document.addEventListener('DOMContentLoaded', function() {
    // Get current location button
    const getLocationBtn = document.getElementById('get-location');
    if (getLocationBtn) {
        getLocationBtn.addEventListener('click', getCurrentLocation);
    }

    // Coordinate inputs
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');

    // Update map preview when coordinates change
    if (latitudeInput && longitudeInput) {
        [latitudeInput, longitudeInput].forEach(input => {
            input.addEventListener('input', updateMapPreview);
        });
    }

    // Initialize map preview on page load
    updateMapPreview();

    function getCurrentLocation() {
        if (!navigator.geolocation) {
            alert('Geolocation is not supported by this browser.');
            return;
        }

        // Show loading state
        getLocationBtn.disabled = true;
        getLocationBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Getting Location...';

        navigator.geolocation.getCurrentPosition(
            function(position) {
                // Success
                latitudeInput.value = position.coords.latitude.toFixed(6);
                longitudeInput.value = position.coords.longitude.toFixed(6);

                // Reset button
                getLocationBtn.disabled = false;
                getLocationBtn.innerHTML = '<i class="fa-solid fa-crosshairs mr-2"></i>Get Current Location';

                // Update map preview
                updateMapPreview();

                // Show success message
                showNotification('Location retrieved successfully!', 'success');
            },
            function(error) {
                // Error
                let errorMessage = 'Unable to retrieve location.';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = 'Location access denied by user.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = 'Location information unavailable.';
                        break;
                    case error.TIMEOUT:
                        errorMessage = 'Location request timed out.';
                        break;
                }

                // Reset button
                getLocationBtn.disabled = false;
                getLocationBtn.innerHTML = '<i class="fa-solid fa-crosshairs mr-2"></i>Get Current Location';

                // Show error message
                showNotification(errorMessage, 'error');
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 300000 // 5 minutes
            }
        );
    }

    function updateMapPreview() {
        const latitude = parseFloat(latitudeInput?.value);
        const longitude = parseFloat(longitudeInput?.value);
        const mapContainer = document.getElementById('map-container');

        if (!mapContainer) return;

        if (latitude && longitude && !isNaN(latitude) && !isNaN(longitude)) {
            // Valid coordinates - show map preview
            mapContainer.innerHTML = `
                <div class="text-center text-gray-500 dark:text-gray-400">
                    <i class="fa-solid fa-map-marked-alt text-3xl mb-2"></i>
                    <p>Location Preview</p>
                    <p class="text-xs">Latitude: ${latitude.toFixed(6)}, Longitude: ${longitude.toFixed(6)}</p>
                    <p class="text-xs text-gray-400">Map integration would go here</p>
                </div>
            `;
            mapContainer.className = 'w-full h-64 bg-green-50 dark:bg-green-900/20 rounded-lg flex items-center justify-center border-2 border-green-200 dark:border-green-800';
        } else {
            // Invalid coordinates - show placeholder
            mapContainer.innerHTML = `
                <div class="text-center text-gray-500 dark:text-gray-400">
                    <i class="fa-solid fa-map-marked-alt text-3xl mb-2"></i>
                    <p>Enter coordinates above to preview location</p>
                </div>
            `;
            mapContainer.className = 'w-full h-64 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center';
        }
    }

    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;

        // Set colors based on type
        const colors = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            warning: 'bg-yellow-500 text-black',
            info: 'bg-blue-500 text-white'
        };

        notification.classList.add(...colors[type].split(' '));
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fa-solid ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;

        // Add to page
        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        // Remove after 5 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 5000);
    }

    // Form validation
    const branchForm = document.querySelector('form[action*="/branches"]');
    if (branchForm) {
        branchForm.addEventListener('submit', function(e) {
            const latitude = parseFloat(latitudeInput?.value);
            const longitude = parseFloat(longitudeInput?.value);

            // Validate coordinates if provided
            if (latitudeInput?.value && longitudeInput?.value) {
                if (isNaN(latitude) || latitude < -90 || latitude > 90) {
                    e.preventDefault();
                    showNotification('Please enter a valid latitude between -90 and 90.', 'error');
                    latitudeInput.focus();
                    return false;
                }

                if (isNaN(longitude) || longitude < -180 || longitude > 180) {
                    e.preventDefault();
                    showNotification('Please enter a valid longitude between -180 and 180.', 'error');
                    longitudeInput.focus();
                    return false;
                }
            }

            return true;
        });
    }

    // Add "Get Current Location" button to create/edit forms if coordinates are empty
    if (latitudeInput && longitudeInput && !latitudeInput.value && !longitudeInput.value) {
        const locationButtonContainer = document.createElement('div');
        locationButtonContainer.className = 'md:col-span-2';
        locationButtonContainer.innerHTML = `
            <button type="button" id="get-location" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 transition-colors duration-200">
                <i class="fa-solid fa-crosshairs mr-2"></i>Get Current Location
            </button>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Use GPS to automatically fill coordinates</p>
        `;

        // Insert after longitude input
        longitudeInput.parentNode.parentNode.insertBefore(locationButtonContainer, longitudeInput.parentNode.nextSibling);

        // Re-bind the event listener
        document.getElementById('get-location').addEventListener('click', getCurrentLocation);
    }
});