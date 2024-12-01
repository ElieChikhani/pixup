function loadAlbums() {
    const userId = getUserId(); 
    const searchTerm = document.getElementById('searchTerm').value; 
    const order = document.getElementById('orderSelect').value; 

    const url = `http://localhost/PIXUP/dbModule/search.php?order=${order}&user_id=${userId}&category=${searchTerm}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const albumsContainer = document.getElementById('albumsContainer');
            if (data.success && data.data.length > 0) {
                albumsContainer.innerHTML = ''; 
                data.data.forEach(album => {
                    const isDefault = album.album_name.toLowerCase() === 'all';
                    const albumCard = document.createElement('div');
                    albumCard.classList.add('col-md-4', 'col-sm-6');

                    albumCard.innerHTML = `
                        <div class="card">
                            <img src="${album.recent_image_path}" class="card-img-top" alt="${album.album_name}">
                            <div class="card-body">
                                <h5 class="card-title">${album.album_name}</h5>
                                <p class="card-text">${isDefault ? "This is your default album." : "Custom album."}</p>
                                <a href="galleryComps/viewAlbum.php?id=${album.album_id}" class="btn btn-outline-primary mb-2">
                                    View Album
                                </a>
                                ${!isDefault ? `
                                    <div class="d-flex justify-content-between">
                                        <a href="galleryComps/editAlbum.php?id=${album.album_id}" class="btn btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="galleryComps/deleteAlbum.php?id=${album.album_id}" class="btn btn-delete">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    `;

                    albumsContainer.appendChild(albumCard);
                });
            } else {
                albumsContainer.innerHTML = "<p>No albums found. <a href='galleryComps/createAlbum.php'>Create your first album</a>!</p>";
            }
        })
        .catch(error => {
            console.error('Error fetching albums:', error);
        });
}

function getUserId() { 
    const userId = sessionStorage.getItem('user_id');
    
    if (userId) {
        return userId; 
    } else {
        console.log("User ID not found.");
        return null;
    }
}

document.addEventListener('DOMContentLoaded', loadAlbums);
