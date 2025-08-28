$(document).ready(function () {
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
   
    const urlParams = new URLSearchParams(window.location.search);
    const videoId = urlParams.get('id');

    if (videoId) {
        // Fetching video data 
        $.ajax({
            url: '../php/get_video.php',
            type: 'GET',
            data: { id: videoId },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#video_id').val(response.video.id);
                    $('#title').val(response.video.title);
                    $('#description').val(response.video.description);
                    $('#subscription_level').val(response.video.subscription_level);
                    $('#currentThumbnail').attr('src', '../' + response.video.thumbnail_path);
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr, status, error) {
                alert('An error occurred while fetching video data: ' + error);
                console.error('Error Status:', status);
                console.error('Error Thrown:', error);
                console.error('Response Text:', xhr.responseText);
            }
        });
    } else {
        console.log('No video ID specified.')
    }

    $('#editForm').submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: '../php/edit_video.php',
            type: 'POST',
            data: formData,
            contentType: false, 
            processData: false, 
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    alert(response.message);
                    window.location.href = 'dashboard.html';
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr, status, error) {
                alert('An error occurred while updating the video: ' + error);
                console.error('Error Status:', status);
                console.error('Error Thrown:', error);
                console.error('Response Text:', xhr.responseText);
            }
        });
    });
});
