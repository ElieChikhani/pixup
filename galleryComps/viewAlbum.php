<?php
include '../dbModule/connectToDB.php';

if (!isset($_GET['id'])) {
    die("Album ID not specified.");
}

$album_id = intval($_GET['id']);

$sql_album = "SELECT * FROM albums WHERE id = $album_id";
$result_album = $conn->query($sql_album);

if ($result_album->num_rows == 0) {
    die("Album not found.");
}

$album = $result_album->fetch_assoc();

$order = isset($_GET['order']) ? $_GET['order'] : 'recent';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
$saved = isset($_GET['saved']) ? $_GET['saved'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo htmlspecialchars($album['name']); ?> - PixUp</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    
    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="styles/generalStyle.css" rel="stylesheet" />
    <link href="styles/indexStyle.css" rel="stylesheet" />
</head>
<body>

<main class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-4"><?php echo htmlspecialchars($album['name']); ?></h1>
        <a href="galleryComps/editAlbum.php?id=<?php echo $album_id; ?>" class="btn btn-outline-primary">
            <i class="fas fa-edit"></i> Edit Album  
        </a>
    </div>

    <p><?php echo htmlspecialchars($album['description']); ?></p>
    <p class="text-muted">Created on: <?php echo date("F j, Y", strtotime($album['creation_date'])); ?></p>
    <hr>

    <a href="../gallery.php" class="btn btn-secondary mb-4">
        <i class="fas fa-arrow-left"></i> Back to Gallery
    </a>

    <?php
    $_GET['album_id'] = $album_id;
    $_GET['order'] = $order;
    $_GET['search'] = $search;
    $_GET['user_id'] = $user_id;
    $_GET['saved'] = $saved;

    include '../components/imageGrid.php';
    ?>

</main>

<!-- Bootstrap JS Libraries -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

</body>
</html>
