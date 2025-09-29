// Profile Settings JavaScript
// Avatar preview functionality
function previewAvatar(input) {
    const preview = document.getElementById('avatar-preview');
    const previewImg = document.getElementById('avatar-preview-img');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        }

        reader.readAsDataURL(input.files[0]);
    } else {
        preview.classList.add('hidden');
    }
}

// Password validation
document.addEventListener('DOMContentLoaded', function() {
    const currentPassword = document.getElementById('current_password');
    const newPassword = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');
    const passwordForm = document.getElementById('password-form');
    const submitButton = document.getElementById('update-password-btn');
    const validationHint = document.getElementById('validation-hint');
    const errorAlert = document.getElementById('password-error-alert');
    const errorMessage = document.getElementById('password-error-message');

    let currentPasswordValid = false;
    let passwordsMatch = false;
    let newPasswordValid = false;

    // Function to show error alert
    function showErrorAlert(message) {
        errorMessage.textContent = message;
        errorAlert.classList.remove('hidden');
        
        // Scroll to alert
        errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });

        // Auto hide after 5 seconds
        setTimeout(() => {
            errorAlert.classList.add('hidden');
        }, 5000);
    }

    // Function to hide error alert
    function hideErrorAlert() {
        errorAlert.classList.add('hidden');
    }

    // Function to update submit button state
    function updateSubmitButton() {
        const allFieldsFilled = currentPassword.value.trim().length > 0 && 
                               newPassword.value.length > 0 && 
                               confirmPassword.value.length > 0;

        const allValid = currentPasswordValid && passwordsMatch && newPasswordValid;

        if (!allFieldsFilled) {
            submitButton.disabled = true;
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
            validationHint.className = 'text-sm text-gray-500 dark:text-gray-400';
        } else if (!allValid) {
            submitButton.disabled = true;
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
            
            if (!currentPasswordValid) {
                validationHint.textContent = 'Current password is incorrect';
            } else if (!newPasswordValid) {
                validationHint.textContent = 'New password must be at least 8 characters';
            } else if (!passwordsMatch) {
                validationHint.textContent = 'Passwords do not match';
            }
            validationHint.className = 'text-sm text-red-600 dark:text-red-400';
        } else {
            submitButton.disabled = false;
            submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
            validationHint.textContent = '✓ Ready to update';
            validationHint.className = 'text-sm text-green-600 dark:text-green-400';
        }
    }

    // Current password validation
    currentPassword.addEventListener('input', function() {
        hideErrorAlert();
        if (currentPassword.value.length === 0) {
            currentPasswordValid = false;
            document.getElementById('current-password-status').textContent = '';
            updateSubmitButton();
        }
    });

    currentPassword.addEventListener('blur', function() {
        validateCurrentPassword();
    });

    function validateCurrentPassword() {
        const password = currentPassword.value.trim();
        const statusDiv = document.getElementById('current-password-status');

        if (password.length === 0) {
            currentPasswordValid = false;
            statusDiv.textContent = '';
            statusDiv.className = 'mt-1 text-sm';
            updateSubmitButton();
            return;
        }

        // Show loading state
        statusDiv.textContent = 'Validating...';
        statusDiv.className = 'mt-1 text-sm text-blue-600';

        // Check if current password is correct via AJAX
        fetch('/superadmin/settings/validate-password', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
            body: JSON.stringify({ password: password })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            currentPasswordValid = data.valid;
            if (data.valid) {
                statusDiv.textContent = '✓ Current password is correct';
                statusDiv.className = 'mt-1 text-sm text-green-600';
                hideErrorAlert();
            } else {
                statusDiv.textContent = '✗ Current password is incorrect';
                statusDiv.className = 'mt-1 text-sm text-red-600';
                showErrorAlert('Current password is incorrect. Please enter the correct password.');
            }
            updateSubmitButton();
        })
        .catch(error => {
            console.error('Error validating password:', error);
            currentPasswordValid = false;
            statusDiv.textContent = '✗ Error validating password';
            statusDiv.className = 'mt-1 text-sm text-red-600';
            showErrorAlert('Error validating password. Please try again.');
            updateSubmitButton();
        });
    }

    // New password validation
    newPassword.addEventListener('input', function() {
        hideErrorAlert();
        validateNewPassword();
        validatePasswordMatch();
    });

    function validateNewPassword() {
        const password = newPassword.value;
        newPasswordValid = password.length >= 8;
        updateSubmitButton();
    }

    // Password confirmation validation
    confirmPassword.addEventListener('input', function() {
        hideErrorAlert();
        validatePasswordMatch();
    });

    function validatePasswordMatch() {
        const newPass = newPassword.value;
        const confirmPass = confirmPassword.value;
        const statusDiv = document.getElementById('password-match-status');

        if (confirmPass.length === 0) {
            passwordsMatch = false;
            statusDiv.textContent = '';
            statusDiv.className = 'mt-1 text-sm';
        } else if (newPass === confirmPass) {
            passwordsMatch = true;
            statusDiv.textContent = '✓ Passwords match';
            statusDiv.className = 'mt-1 text-sm text-green-600';
        } else {
            passwordsMatch = false;
            statusDiv.textContent = '✗ Passwords do not match';
            statusDiv.className = 'mt-1 text-sm text-red-600';
        }

        updateSubmitButton();
    }

    // Form submission
    passwordForm.addEventListener('submit', function(e) {
        // Double check validation before submit
        if (!currentPasswordValid || !passwordsMatch || !newPasswordValid) {
            e.preventDefault();
            
            let errorMsg = '';
            if (!currentPasswordValid) {
                errorMsg = 'Current password is incorrect. Please check and try again.';
            } else if (!newPasswordValid) {
                errorMsg = 'New password must be at least 8 characters long.';
            } else if (!passwordsMatch) {
                errorMsg = 'New password and confirmation do not match. Please check and try again.';
            }
            
            showErrorAlert(errorMsg);
            return false;
        }

        // Disable submit button to prevent double submission
        submitButton.disabled = true;
        submitButton.textContent = 'Updating...';
        submitButton.classList.add('opacity-50', 'cursor-not-allowed');
    });

    // Initial state
    updateSubmitButton();
}); 