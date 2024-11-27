<?php
session_start();
include 'connectToDB.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$albumId = intval($_GET['id']);

$sql = "SELECT * FROM albums WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $albumId);
$stmt->execute();
$result = $stmt->get_result();
$album = $result->fetch_assoc();

if (!$album) {
    header("Location: gallery.php");
    exit();
}

$sqlRecentImage = "SELECT image_path FROM images WHERE album_id = ? ORDER BY upload_date DESC LIMIT 1";
$stmtRecentImage = $conn->prepare($sqlRecentImage);
$stmtRecentImage->bind_param("i", $albumId);
$stmtRecentImage->execute();
$resultRecentImage = $stmtRecentImage->get_result();
$recentImage = $resultRecentImage->fetch_assoc();

$thumbnail = $recentImage ? $recentImage['image_path'] : $album['thumbnail'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);

    $sql = "UPDATE albums SET name = ?, description = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $name, $description, $albumId);
    $stmt->execute();

    header("Location: gallery.php?success=Album edited successfully!");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Album</title>
    <link rel="stylesheet" href="styles/generalStyle.css">
</head>
<body>
    <h1>Edit Album</h1>
    <form method="POST">
        <label for="name">Album Title:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($album['name']); ?>" required><br><br>
        
        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($album['description']); ?></textarea><br><br>

        <label>Current Thumbnail:</label>
        <?php if ($thumbnail): ?>
            <div>
                <img src="<?php echo htmlspecialchars($thumbnail); ?>" alt="Album Thumbnail" style="max-width: 150px; max-height: 150px;">
            </div>
        <?php else: ?>
            <p>No thumbnail set.</p>
        <?php endif; ?>
        <br><br>

        <button type="submit">Save Changes</button>
    </form>
</body>
</html>
