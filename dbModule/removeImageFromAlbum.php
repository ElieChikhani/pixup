<?php 

$image_id = isset($_POST["image_id"]) ? ($_POST["image_id"]) :NULL;
$album_id = isset($_POST["album_id"]) ? ($_POST["album_id"]) :NULL; 

if(!isset($_SESSION)) session_start(); 

include "../components/canEditAlbum.php";

if(!canEditAlbum($album_id,$_SESSION['user_id'])){
    $message = "Not authorized to edit this album right now";
    $success = false; 
    header("Location: index.php?success=$success&message=$message");
    exit(); 
}


if(!empty($image_id) && !empty($album_id)) {
include "connectToDB.php";
$stmt = $conn->prepare("DELETE FROM album_image WHERE image_id = ? AND album_id = ?");
$stmt->bind_param('ss', $image_id, $album_id); 

if (!$result=$stmt->execute()) {
    $response  = [
        'success' => false,
        'message' => "Error removing album"
    ];
}else {

    $response  = [
        'success' => true
    ];

}
$stmt->close();
$conn->close(); 
}else {
    $response  = [
        'success' => false,
        'message' => "No given parameters"
    ];

}

header("Content-Type: application/json");
echo json_encode($response);



?>