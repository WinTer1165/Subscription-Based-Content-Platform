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
        }
    });
    loadVideos();

    function loadVideos() {
        $.ajax({
            url: '../php/get_videos.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.success) {
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
        const tableBody = $('#videoTable tbody');
        tableBody.empty();

        if (videos.length === 0) {
            tableBody.append('<tr><td colspan="5">No videos uploaded yet.</td></tr>');
        } else {
            videos.forEach(function (video) {
                
                const thumbnailPath = '../' + video.thumbnail_path;
                const thumbnailSrc = thumbnailPath + '?t=' + new Date().getTime();

                const row = `
                    <tr>
                        <td><img src="${thumbnailSrc}" alt="${video.title}" style="width: 100px; height: auto;"></td>
                        <td>${video.title}</td>
                        <td>${video.subscription_level}</td>
                        <td>${new Date(video.created_at).toLocaleString()}</td>
                        <td>
                            <a href="edit.html?id=${video.id}" class="btn btn-edit">Edit</a>
                            <button class="btn btn-delete" data-id="${video.id}">Delete</button>
                        </td>
                    </tr>
                `;
                tableBody.append(row);
            });
        }
    }

    // Delete video
    $(document).on('click', '.btn-delete', function () {
        const videoId = $(this).data('id');
        if (confirm('Are you sure you want to delete this video?')) {
            $.ajax({
                url: '../php/delete_video.php',
                type: 'POST',
                data: { id: videoId },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        alert(response.message);
                        loadVideos();
                    } else {
                        alert(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the video.');
                }
            });
        }
    });
});
