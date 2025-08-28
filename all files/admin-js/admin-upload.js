$(document).ready(function() {
    $.ajax({
        url: '../php/get_admin_info.php', 
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (!response.logged_in) {
                window.location.href = "login.html";
            }
        },
        error: function (xhr, status, error) {
            console.error('Error:', error);
            alert('An error occurred while checking login status.');
        }
    });
    $('#uploadForm').submit(function(e) {
        e.preventDefault();
        if (validateForm()) {
            var formData = new FormData(this);
            $.ajax({
                url: '../php/upload_video.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        $('#uploadForm')[0].reset();
                        window.location.href = 'dashboard.html';
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    console.error('Response Text:', xhr.responseText);
                }
            });
        }
    });


    function validateForm() {
        let isValid = true;

        // Title validation
        const title = document.getElementById('title');
        if (title.value.trim() === '') {
            showError(title, 'Title is required');
            isValid = false;
        } else {
            clearError(title);
        }

        // Description validation
        const description = document.getElementById('description');
        if (description.value.trim() === '') {
            showError(description, 'Description is required');
            isValid = false;
        } else {
            clearError(description);
        }

        // Subscription level validation
        const subscriptionLevel = document.getElementById('subscription_level');
        if (subscriptionLevel.value === '') {
            showError(subscriptionLevel, 'Please select a subscription level');
            isValid = false;
        } else {
            clearError(subscriptionLevel);
        }

        // Video file validation
        const videoFile = document.getElementById('video_file');
        if (videoFile.files.length === 0) {
            showError(videoFile, 'Please select a video file');
            isValid = false;
        } else {
            clearError(videoFile);
        }

        // Thumbnail validation
        const thumbnail = document.getElementById('thumbnail');
        if (thumbnail.files.length === 0) {
            showError(thumbnail, 'Please select a thumbnail image');
            isValid = false;
        } else {
            clearError(thumbnail);
        }

        return isValid;
    }

    function showError(input, message) {
        const formGroup = input.parentElement;
        let errorElement = formGroup.querySelector('.error');
        
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'error';
            formGroup.appendChild(errorElement);
        }
        
        errorElement.textContent = message;
    }

    function clearError(input) {
        const formGroup = input.parentElement;
        const errorElement = formGroup.querySelector('.error');
        
        if (errorElement) {
            formGroup.removeChild(errorElement);
        }
    }
});