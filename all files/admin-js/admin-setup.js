$(document).ready(function() {
    $('#adminSetupForm').submit(function(e) {
        e.preventDefault();
        if (validateForm()) {
            $.ajax({
                url: 'php/admin_register.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        window.location.href = 'admin/login.html';
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        }
    });

    function validateForm() {
        let isValid = true;

        // Username validation
        const username = $('#username');
        if (username.val().trim().length < 3) {
            showError(username, 'Username must be at least 3 characters long');
            isValid = false;
        } else {
            clearError(username);
        }

        // Password validation
        const password = $('#password');
        const confirmPassword = $('#confirm-password');
        if (password.val().length < 8) {
            showError(password, 'Password must be at least 8 characters long');
            isValid = false;
        } else if (password.val() !== confirmPassword.val()) {
            showError(confirmPassword, 'Passwords do not match');
            isValid = false;
        } else {
            clearError(password);
            clearError(confirmPassword);
        }

        return isValid;
    }

    function showError(input, message) {
        const formGroup = input.parent();
        let errorElement = formGroup.find('.error');
        
        if (errorElement.length === 0) {
            errorElement = $('<div class="error"></div>');
            formGroup.append(errorElement);
        }
        
        errorElement.text(message);
        input.addClass('input-error');
    }

    function clearError(input) {
        const formGroup = input.parent();
        const errorElement = formGroup.find('.error');
        
        if (errorElement.length > 0) {
            errorElement.remove();
        }
        
        input.removeClass('input-error');
    }
});