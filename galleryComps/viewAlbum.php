<?php
include 'connectToDB.php';

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<main class="container py-5">
    <h1 class="display-4"><?php echo htmlspecialchars($album['name']); ?></h1>
    <p><?php echo htmlspecialchars($album['description']); ?></p>
    <p class="text-muted">Created on: <?php echo date("F j, Y", strtotime($album['creation_date'])); ?></p>
    <hr>

    <?php
    $_GET['album_id'] = $album_id;
    $_GET['order'] = $order;
    $_GET['search'] = $search;
    $_GET['user_id'] = $user_id;
    $_GET['saved'] = $saved;

    include 'imageGrid.php';
    ?>

</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
