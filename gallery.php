<!DOCTYPE html>
<html lang="en">
<head>
    <title>PixUp</title>
  
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link href="styles/generalStyle.css" rel="stylesheet" />
    <link href="styles/indexStyle.css" rel="stylesheet" />

    <style>
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0px 4px 15px rgba(0,0,0,0.2);
            transition: all 0.3s ease-in-out;
        }

        .btn-edit:hover {
            background-color: #0056b3;
            border-color: #007bff;
        }

        .btn-delete {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-delete:hover {
            background-color: #b02a37;
            border-color: #8a1f29;
        }
    </style>
</head>
<body>

<header></header>

<main class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-4">Your Albums</h1>
        <a href="galleryComps/createAlbum.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Album  
        </a>
    </div>

    <div class="d-flex mb-4">
        <input type="text" id="searchTerm" class="form-control me-2" placeholder="Search Albums">
        <select id="orderSelect" class="form-control me-2">
            <option value="recent">Most Recent</option>
            <option value="alphabetical">Alphabetical</option>
            <option value="oldest">Oldest First</option>
        </select>
        <button class="btn btn-secondary" onclick="loadAlbums()">Search</button>
    </div>

    <div id="albumsContainer" class="row g-4">
        <!-- dynamically added here -->
    </div>
</main>

<!-- Bootstrap JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
      integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
      crossorigin="anonymous"></script>

<script>
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

    function getUserId() { //might need modif
        const userId = sessionStorage.getItem('user_id');
        
        if (userId) {
            return userId; 
        } else {
            console.log("User ID not found.");
            return null;
        }
    }

    document.addEventListener('DOMContentLoaded', loadAlbums);
</script>

</body>
</html>
