
<?php

if(!isset($_SESSION)) session_start(); 

function createAlbum($album_name, $album_description, $user_id) {

if(!empty($album_name)) {

 include "connectToDB.php"; 

// Checking if there is already an album with a similar name
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

$unique_id = uniqid(); 


$sql = "INSERT INTO albums (album_id,user_id, album_name, album_description) VALUES (?,?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $unique_id ,$user_id, $album_name, $album_description);

if ($stmt->execute()) {

    if(isset($_SESSION['user_id'])&&!empty($_SESSION['user_id'])){ //in case the function is used to create the All album of a just signed up user
    $success = true; 
    $message = "Album created succesfully"; 
    header("Location: gallery.php?success=$success&message=$message");
    exit();
    }
} else {
    $success = false; 
    $message = "Could not create album at this moment"; 
    header("Location: gallery.php?success=$success&message=$message");
}
}else {
    $success = false; 
    $message = "Could not create album at this moment"; 
    header("Location: gallery.php?success=$success&message=$message");

}

}

?>