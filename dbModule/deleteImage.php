<?php

if(!isset($_SESSION)) session_start();

//deleting an image consists of deleting in all tabels rows containing this image id.
//to avoid pasisng the image id in javascript we take the current_image_id stored in the session 
$image_id = $_SESSION['current_image_id'];
$image_path =$_SESSION['current_image_path'];


$user_id = $_SESSION['user_id'];
include 'connectToDB.php'; 

// Verifying that the image belongs to the user
$stmt = $conn->prepare('SELECT * FROM images WHERE image_id = ? AND user_id = ?');
$stmt->bind_param('ss', $image_id, $user_id);
$stmt->execute();

$result = $stmt->get_result();
$image = $result->fetch_assoc();


if($image) {

//tables : 
$tables = ['images','album_image','save_image','category_image'];


foreach ($tables as $table) {
    $stmt = $conn->prepare("DELETE FROM $table WHERE image_id = ?");
    $stmt->bind_param('s', $image_id); 
    if (!$stmt->execute()) 
        $response  = [
            'success' => false,
            'message' => "Error deleting image from $table: " . $stmt->error
        ];
        break; 
    }
    $stmt->close();


//decrement the imageCount of user 
$user_id = $_SESSION['user_id']; 
$stmt = $conn->prepare("UPDATE users SET imageCount = imageCount - 1 WHERE user_id = ?");
$stmt->bind_param('s', $user_id); 
if (!$stmt->execute()) {
    $response  = [
        'success' => false,
        'message' => "Error decrementing user image Count "
    ];
}
$stmt->close();

$path='../'.$image_path; 

// Delete the file
if (file_exists($path)) {
    if (!unlink($path)) {
        $response  = [
            'success' => false,
            'message' => "Error deleting image from directory",
        ];
    }
} else {
    $response  = [
        'success' => false,
        'message' => "file does not exist",
    ];
}

}else {
    $response  = [
        'success' => false,
        'message' => "Action not allowed",
    ];

}

if(!isset($response)){
    $response  = [
        'success' => true,
        'message' => "Image deleleted succefuly",
    ];

}

header('Content-Type: application/json');
echo json_encode($response);

?>