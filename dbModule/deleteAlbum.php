<?php
if(!isset($_SESSION))session_start();
include '../dbModule/connectToDB.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.php"); // Redirect to login page if not logged in
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: ../index.php");
    exit();
}

$albumId = isset($_GET['id']) ?($_GET['id']):null;
$albumName = isset($_GET['album_name']) ?($_GET['album_name']):null;;
$userId = $_SESSION['user_id']; 


if(empty($userId) || empty($albumId) || empty($albumName)) {
    $success = false; 
    $message = "Failed to delete album";
    header("Location: ../gallery.php?success=$success&message=$message.");
    exit();
}

// Display confirmation form
echo "
    <div style='text-align: center; font-family: Arial, sans-serif; padding: 20px; background-color: #f9f9f9; border: 1px solid #ccc; border-radius: 10px; margin: 0 auto; width:400px'>
        <h2 style='color: #333;'>Are you sure you want to delete the album:</h2>
        <h3 style='color: #d9534f;'>" . htmlspecialchars($albumName) . "</h3>
        <form method='POST' style='margin-top: 20px;'>
            <input type='hidden' name='album_id' value='" . htmlspecialchars($albumId) . "'>
            <button type='submit' name='confirm' value='yes' style='padding: 10px 20px; background-color: #d9534f; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-right: 10px;'>Yes, Delete</button>
            <button type='submit' name='cancel' value='no' style='padding: 10px 20px; background-color: #5bc0de; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;'>Cancel</button>
        </form>
    </div>
";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
    
        $deleteSql = "DELETE FROM albums WHERE album_id = ? AND user_id = ?";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bind_param("ss", $albumId, $userId);

        if ($deleteStmt->execute()) {
            $success = true; 
            $message = "Album deleted successfully";
            header("Location: ../gallery.php?success=$success&message=$message.");
        } else {
            $success = false; 
            $message = "Failed to delete album";
            header("Location: ../gallery.php?success=$success&message=$message.");
        }
        $deleteStmt->close();
        exit();
    }

    if (isset($_POST['cancel']) && $_POST['cancel'] === 'no') {
        header("Location: ../gallery.php");
        exit();
    }
}

   
    

?>
