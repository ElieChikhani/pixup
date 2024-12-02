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

<script src="scripts/gallery.js"></script>

</body>
</html>
