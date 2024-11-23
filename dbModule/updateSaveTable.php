<?php

$image_id = isset($_GET['image_id']) ? (int)$_GET['image_id'] : null;

$user_id = 1; //to be changed


//check if image saved:

$sql = "SELECT * FROM save_image WHERE image_id = $image_id AND user_id = $user_id"; 

include 'connectToDB.php';

$result = $conn->query($sql);

if ($result->num_rows > 0) {
 $saved=true; 
} else {
 $saved=false; 
}


if(!$saved){
    $sql="INSERT INTO save_image(image_id,user_id) VALUES ($image_id,$user_id)"; 
    $action = 'saved';
}else {
    $sql="DELETE FROM save_image WHERE image_id = $image_id AND user_id = $user_id"; 
    $action = 'unsaved';
}

if ($conn->query($sql) === TRUE && $conn->affected_rows>0) {
    $response = ['success' => true,
                 'action'=>$action];
}else {
    $response = ['success' => false];
}

$conn->close();

// Send JSON response
header("Content-Type: application/json");
echo json_encode($response);






?>