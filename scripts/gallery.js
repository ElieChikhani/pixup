function loadAlbums() {
    const userId = getUserId(); 

    const url = `dbModule/searchAlbum.php?user_id=${userId}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const albumsContainer = document.getElementById('albumsContainer');
            if (data.success) {
                console.log(data);
                console.log(url);
                albumsContainer.innerHTML = ''; 
                data.albums.forEach(album => {
                    const isDefault = album.album_name.toLowerCase() === 'all';
                    const albumCard = document.createElement('div');
                    albumCard.classList.add('col-md-4', 'col-sm-6');
                    recent_image_path = !album.recent_image_path?'webPictures/emptyAlbum.jpg':album.recent_image_path;

                    albumCard.innerHTML = `
                        <div class="card">
                            <img src="${recent_image_path}" class="card-img-top preview" alt="${album.album_name}">
                            <div class="card-body">
                                <h5 class="card-title">${album.album_name}</h5>
                                <p class="card-text">${isDefault ? "This is a default album." : "Custom album."}</p>
                                <a href="viewAlbum.php?id=${album.album_id}" class="btn btn-outline-primary mb-2">
                                    View Album
                                </a>
                            </div>
                        </div>
                    `;

                    albumsContainer.appendChild(albumCard);
                });
            } else {
                albumsContainer.innerHTML = "<p>No albums found. <a href='../galleryComps/createAlbum.php'>Create your first album</a>!</p>";
            }
        })
        .catch(error => {
            console.error('Error fetching albums:', error);
        });
}

function getUserId() { 
    const userId = sessionStorage.getItem('user_id');
    console.log(userId);
    if (userId) {
        return userId; 
    } else {
        console.log("User ID not found.");
        return null;
    }
}

document.addEventListener('DOMContentLoaded', loadAlbums);
