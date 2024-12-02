<?php


if (!isset($_GET['id'])) {
    die("Album ID not specified.");
}

$album_id = intval($_GET['id']);
if (!$album_id) {
    die('album id not given');
}

$albumInfoURL = "http://localhost/PIXUP/dbModule/getAlbumInfo.php?album_id=$album_id"; 
$result = file_get_contents($albumInfoURL); 
if($result === false ){
    die ("Error occured while precessing info"); 
}

$data = json_decode($result, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    $error = json_last_error_msg();
    die ("Error occured in json");
}

if($data['success']){
    $album = $data['album_info']; 
}else {
    die ('no matching album');
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo htmlspecialchars($album['album_name']); ?> - PixUp</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    
    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="styles/generalStyle.css" rel="stylesheet" />
    <link href="styles/albumView.css" rel="stylesheet" />
    
</head>
<body>

<main>

    <section id="album_info">

    <a href="gallery.php" class="btn btn-secondary mb-4">
        <i class="fas fa-arrow-left"></i> Back to Gallery
    </a>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-4"><?php echo htmlspecialchars($album['album_name']); ?></h1>
        <a href="editAlbum.php?id=<?php echo $album_id; ?>" class="btn btn-outline-primary">
            <i class="fas fa-edit"></i> Edit Album  
        </a>
    </div>

    <p><?php echo htmlspecialchars($album['album_description']); ?></p>
    <p class="text-muted">Created on: <?php echo date("F j, Y", strtotime($album['album_creation_date'])); ?></p>

    <form id='search-form' album_id="<?php echo htmlspecialchars($album['album_id']); ?>">

    <input id='search-bar' class='form-control me-sm-2' type='text'
    placeholder='Search for photos' name='search'/>

    <select class='form-select form-select-lg' name='order' id='order-select'>
        <option value='recent'>Recent</option>
        <option value='popular'>Most Popular</option>
    </select>

</form>

    </section>


    <?php
    include 'components/imageGrid.php';
    ?>



</main>

 <!--Masonry.js for grid layout-->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/4.2.2/masonry.pkgd.min.js"></script>

<!--check the loading state of an image-->
<script src="https://unpkg.com/imagesloaded/imagesloaded.pkgd.min.js"></script>

<script src="scripts/allGridScript.js"></script>

<!-- Bootstrap JS Libraries -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

</body>
</html>
