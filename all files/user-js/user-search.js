$(document).ready(function () {
    // Debounce to stop excessive AJAX calls
    function debounce(func, delay) {
        let debounceTimer;
        return function () {
            const context = this;
            const args = arguments;
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => func.apply(context, args), delay);
        };
    }

    // Event listener for the search box
    $('#search-box').on('keyup', debounce(function () {
        const query = $(this).val().trim();

        if (query.length > 0) {
            searchVideos(query);
        } else {
            $('#video-grid').empty(); 
        }
    }, 300)); 

  
    function searchVideos(query) {
        $.ajax({
            url: '../php/search_videos.php',
            type: 'GET',
            data: { q: query },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    displayVideos(response.videos, query);
                } else {
                    $('#video-grid').empty().append('<p>No videos found.</p>');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                alert('An error occurred while searching for videos.');
            }
        });
    }

    function displayVideos(videos, query = '') {
        const videoGrid = $('#video-grid');
        videoGrid.empty();

        if (videos.length === 0) {
            videoGrid.append('<p>No videos found.</p>');
        } else {
            const regex = new RegExp(`(${escapeRegex(query)})`, 'gi');
            videos.forEach(function (video) {
                let title = escapeHtml(video.title);
                let description = escapeHtml(video.description);
                if (query !== '') {
                    title = title.replace(regex, '<mark>$1</mark>');
                    description = description.replace(regex, '<mark>$1</mark>');
                }
                const videoItem = `
                    <div class="video-item">
                        <img src="${escapeHtml(video.thumbnail_path)}" alt="${escapeHtml(video.title)}">
                        <h3>${title}</h3>
                        <p>${description}</p>
                        <a href="#" class="btn" data-video-id="${video.id}" data-video-path="${escapeHtml(video.file_path)}">Watch</a>
                    </div>
                `;
                videoGrid.append(videoItem);
            });
        }
    }

    $(document).on('click', '.btn', function (e) {
        e.preventDefault();
        const videoPath = $(this).data('video-path');

        $('#video-source').attr('src', videoPath);
        $('#video-player')[0].load();  
        $('#video-modal').fadeIn();
    });

   
    $('#close-modal').on('click', function () {
        $('#video-modal').fadeOut();
        $('#video-player')[0].pause(); 
    });

    function escapeHtml(text) {
        if (!text) return '';
        return text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function escapeRegex(text) {
        return text.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
    }
});
