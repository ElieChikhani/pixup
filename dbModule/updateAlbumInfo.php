<?php 


//updates the name and description of an album 

$album_id = isset($_POST['album_id']) ?  $_POST['album_id']:null; 
$album_name = isset($_POST['album_name']) ? (string)$_POST['album_name'] : '';
$album_description = isset($_POST['album_description']) ? (string)$_POST['album_description'] : '';

if(empty($album_name)){
    $success = false; 
    $message = "Album title cannot be empty";
    header("Location: ../editAlbum.php?id=$album_id&success=$success&message=$message");
    exit(); 
}


if(!isset($_SESSION)) session_start(); 


if(!empty($album_id)) {
include "connectToDB.php";

include "../components/canEditAlbum.php";

if(!canEditAlbum($album_id,$_SESSION['user_id'])){
    $message = "Not authorized to edit this album right now";
    $success = false; 
    header("Location: index.php?success=$success&message=$message");
    exit(); 
}

$user_id = $_SESSION['user_id'];

// Checking if there is already an album with a similar name to increase it's count
$check_sql = "SELECT COUNT(*) as count FROM albums WHERE album_name LIKE ? AND user_id = ?";
$stmt_check = $conn->prepare($check_sql);

$searchName = $album_name . '%';
$stmt_check->bind_param("ss", $searchName, $user_id);
$stmt_check->execute();

$result = $stmt_check->get_result()->fetch_assoc();

if ($result['count'] > 0) {
    $counter = $result['count']; 
    $album_name = $album_name . "($counter)";
} 

$sql = "UPDATE albums SET album_name = ?, album_description = ?  WHERE album_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $album_name, $album_description, $album_id);

if (!$stmt->execute()) {
    $success = false; 
    $message = "not autorized to this action right now";
    $stmt->close();
    header("Location: ../index.php?success=$success&message=$message");
    exit(); 
}else {
    $stmt->close();
    $success = true; 
    $message = "Album info updated!";
    header("Location: ../editAlbum.php?id=$album_id&success=$success&message=$message");
    //header("Location: ../editAlbum.php?album_id=$album_id");
    exit();

}




}

?>