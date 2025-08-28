$(document).ready(function () {

    // Checking if the user is logged in
    $.ajax({
        url: '../php/check_login.php',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (!response.isLoggedIn) {
                window.location.href = "login.html";
            } else {
                loadVideos();
            }
        },
        error: function (xhr, status, error) {
            console.error('Error:', error);
        }
    });
    loadVideos();
    
    function loadVideos() {
        $.ajax({
            url: '../php/get_user_videos.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    //console.log(response)
                    displayVideos(response.videos);
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    function displayVideos(videos) {
        const videoGrid = $('#video-grid');
        videoGrid.empty();

        if (videos.length === 0) {
            videoGrid.append('<p>No videos available.</p>');
        } else {
            videos.forEach(function (video) {
                const videoItem = `
                    <div class="video-item">
                        <img src="${video.thumbnail_path}" alt="${video.title}">
                        <h3>${video.title}</h3>
                        <p>${video.description}</p>
                        <a href="#" class="btn" data-video-id="${video.id}" data-video-path="${video.file_path}">Watch</a>
                    </div>
                `;
                videoGrid.append(videoItem);
            });
        }
    }

    //watch function
    $(document).on('click', '.btn', function (e) {
        e.preventDefault();
        const videoPath = $(this).data('video-path'); 
        console.log("This is the video path:", videoPath); 

        $('#video-source').attr('src', videoPath);
        $('#video-player')[0].load();  
        $('#video-modal').fadeIn();
    });


    //video close
    $('#close-modal').on('click', function () {
        $('#video-modal').fadeOut();
        $('#video-player')[0].pause(); 
    });

});