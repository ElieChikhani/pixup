<?php
session_start();
include '../dbModule/connectToDB.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php"); // Redirect to login page if not logged in
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$albumId = intval($_POST['album_id']); 
$userId = $_SESSION['user_id']; 

if (isset($_POST['confirm'])) {
    $sql = "SELECT thumbnail FROM albums WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $albumId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $album = $result->fetch_assoc();

    if ($album) {
        if (file_exists($album['thumbnail'])) {
            unlink($album['thumbnail']);
        }

        $sql = "DELETE FROM albums WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $albumId, $userId);
        $stmt->execute();

        header("Location: gallery.php?success=Album deleted successfully!");
        exit();
    } else {
        header("Location: gallery.php?error=Album not found or you do not have permission to delete it.");
        exit();
    }
} elseif (isset($_POST['cancel'])) {
    header("Location: gallery.php?id=" . $albumId);
    exit();
} else {
    $sql = "SELECT name FROM albums WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $albumId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $album = $result->fetch_assoc();

    if ($album) {
        echo "<h2>Are you sure you want to delete the album: " . htmlspecialchars($album['name']) . "?</h2>";
        echo "
            <form method='POST'>
                <button type='submit' name='confirm' value='yes'>Yes, Delete</button>
                <button type='submit' name='cancel' value='no'>Cancel</button>
            </form>
        ";
    } else {
        header("Location: gallery.php?error=Album not found or you do not have permission to delete it.");
        exit();
    }
}
?>
