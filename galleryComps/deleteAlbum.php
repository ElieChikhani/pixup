<?php
session_start();
include 'connectToDB.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$albumId = intval($_GET['id']);

if (isset($_POST['confirm'])) {
    $sql = "SELECT thumbnail FROM albums WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $albumId);
    $stmt->execute();
    $result = $stmt->get_result();
    $album = $result->fetch_assoc();

    if ($album) {
        if (file_exists($album['thumbnail'])) {
            unlink($album['thumbnail']);
        }

        $sql = "DELETE FROM albums WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $albumId);
        $stmt->execute();

        header("Location: index.php?success=Album deleted successfully!");
        exit();
    } else {
        header("Location: index.php?error=Album not found.");
        exit();
    }
} elseif (isset($_POST['cancel'])) {
    header("Location: gallery.php?id=" . $albumId);
    exit();
} else {
    $sql = "SELECT name FROM albums WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $albumId);
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
        header("Location: gallery.php?error=Album not found.");
        exit();
    }
}
?>