<?php
session_start();
include 'dbModule/connectToDB.php';

if (!isset($_GET['id'])) {
    header("Location: ../index.php");
    exit();
}

$album_id = intval($_GET['id']);

//storring the current album into the session to securly process any edit action 
$_SESSION['current_album_id'] = $album_id;

include "components/canEditAlbum.php";

if(!canEditAlbum($album_id,$_SESSION['user_id'])){
    $message = "Not authorized to edit this album right now";
    $success = false; 
    header("Location: index.php?success=$success&message=$message");
    exit(); 
}

if (!$album_id) {
    die('album id not given');
}

$albumInfoURL = "http://localhost/PIXUP/dbModule/getAlbumInfo.php?album_id=$album_id";
$result = file_get_contents($albumInfoURL);
if ($result === false) {
    die("Error occured while precessing info");
}

$data = json_decode($result, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    $error = json_last_error_msg();
    die("Error occured in json");
}

if ($data['success']) {
    $album = $data['album_info'];
} else {
    die('no matching album');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Album</title>

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link href="styles/generalStyle.css" rel="stylesheet" />
    <link href="styles/editAlbum.css" rel="stylesheet" />
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">PixUp</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="gallery.php">Back to Gallery</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="container py-5">
        <h1 class="display-4 mb-4">Edit Album</h1>

        <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="name" class="form-label">Album Title</label>
                <input type="text" id="name" name="name" class="form-control"
                    value="<?php echo htmlspecialchars($album['album_name']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-control" rows="4"
                    required><?php echo htmlspecialchars($album['album_description']); ?></textarea>
            </div>

        </form>

        <div class="d-flex justify-content-between align-items-center mb-4">
             <h3 class="display-5"> Manage  Images </h3>
        <a href="addImages.php?id=<?php echo $album_id; ?>" class="btn btn-outline-primary">
            <i class="fas fa-plus"></i> Add images to album
        </a>
        </div>


        <form id='search-form' removable='true' album_id=<?php echo $album_id; ?>>

            <input id='search-bar' class='form-control me-sm-2' type='text' placeholder='Search for photos'
                name='search' />

            <select class='form-select form-select-lg' name='order' id='order-select'>
                <option value='recent'>Recent</option>
                <option value='popular'>Most Popular</option>
            </select>

        </form>

        <?php include "components/imageGrid.php" ?>


    </main>

    <script src="scripts/allGridScript.js"></script>

    <!--Masonry.js for grid layout-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/4.2.2/masonry.pkgd.min.js"></script>

    <!--check the loading state of an image-->
    <script src="https://unpkg.com/imagesloaded/imagesloaded.pkgd.min.js"></script>


    <!-- Bootstrap JS (Popper.js and Bootstrap) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>

</body>

</html>