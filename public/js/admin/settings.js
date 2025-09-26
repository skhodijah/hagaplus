document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.dataset.tab;

            // Remove active state from all tabs
            tabButtons.forEach(btn => {
                btn.classList.remove('border-blue-500', 'text-blue-600', 'dark:text-blue-400');
                btn.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            });

            // Add active state to clicked tab
            this.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            this.classList.add('border-blue-500', 'text-blue-600', 'dark:text-blue-400');

            // Hide all tab contents
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });

            // Show target tab content
            const targetContent = document.querySelector(`.tab-content[data-tab="${targetTab}"]`);
            if (targetContent) {
                targetContent.classList.remove('hidden');
            }
        });
    });

    // Boolean toggle functionality
    const booleanInputs = document.querySelectorAll('input[type="checkbox"]');
    booleanInputs.forEach(input => {
        input.addEventListener('change', function() {
            const label = document.querySelector(`label[for="${this.id}"]`);
            if (label) {
                label.textContent = this.checked ? 'Enabled' : 'Disabled';
            }
        });
    });

    // Reset category functionality
    const resetCategoryButtons = document.querySelectorAll('.reset-category-btn');
    resetCategoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.dataset.category;
            const categoryName = this.textContent.replace('Reset ', '').replace(' to Defaults', '');

            if (confirm(`Are you sure you want to reset all ${categoryName} settings to their default values? This action cannot be undone.`)) {
                resetSettings(category);
            }
        });
    });

    // Reset all functionality
    const resetAllButton = document.getElementById('reset-all');
    if (resetAllButton) {
        resetAllButton.addEventListener('click', function() {
            if (confirm('Are you sure you want to reset ALL settings to their default values? This action cannot be undone.')) {
                resetSettings();
            }
        });
    }

    // Form submission with loading state
    const settingsForm = document.getElementById('settings-form');
    if (settingsForm) {
        settingsForm.addEventListener('submit', function(e) {
            const submitButton = document.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Saving...';
            }
        });
    }

    // Auto-save functionality (optional - can be enabled later)
    let autoSaveTimeout;
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(autoSaveTimeout);
            // Uncomment below lines to enable auto-save after 3 seconds of inactivity
            // autoSaveTimeout = setTimeout(() => {
            //     showNotification('Settings auto-saved', 'info');
            // }, 3000);
        });
    });

    // Validation for specific fields
    const timeInputs = document.querySelectorAll('input[type="time"]');
    timeInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value && !/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/.test(this.value)) {
                showNotification('Please enter a valid time in HH:MM format', 'error');
                this.focus();
            }
        });
    });

    const numberInputs = document.querySelectorAll('input[type="number"]');
    numberInputs.forEach(input => {
        input.addEventListener('blur', function() {
            const value = parseFloat(this.value);
            const min = parseFloat(this.min);
            const max = parseFloat(this.max);

            if (this.value && isNaN(value)) {
                showNotification('Please enter a valid number', 'error');
                this.focus();
                return;
            }

            if (this.min && value < min) {
                showNotification(`Value must be at least ${min}`, 'error');
                this.focus();
                return;
            }

            if (this.max && value > max) {
                showNotification(`Value must be at most ${max}`, 'error');
                this.focus();
                return;
            }
        });
    });

    // Email validation
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value)) {
                showNotification('Please enter a valid email address', 'error');
                this.focus();
            }
        });
    });

    function resetSettings(category = null) {
        const formData = new FormData();
        if (category) {
            formData.append('category', category);
        }

        fetch(`{{ route('admin.settings.reset') }}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message || 'Settings reset successfully', 'success');
                // Reload page after 2 seconds
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                showNotification(data.message || 'Failed to reset settings', 'error');
            }
        })
        .catch(error => {
            console.error('Error resetting settings:', error);
            showNotification('An error occurred while resetting settings', 'error');
        });
    }

    function showNotification(message, type = 'info') {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.settings-notification');
        existingNotifications.forEach(notification => {
            document.body.removeChild(notification);
        });

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full settings-notification`;

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
            if (document.body.contains(notification)) {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }
        }, 5000);
    }

    // Initialize boolean labels on page load
    booleanInputs.forEach(input => {
        const label = document.querySelector(`label[for="${input.id}"]`);
        if (label) {
            label.textContent = input.checked ? 'Enabled' : 'Disabled';
        }
    });

    // Show success message if redirected with success
    // This will be handled by the Blade template
});