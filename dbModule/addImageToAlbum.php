<?php 

$image_id = isset($_POST["image_id"]) ? (int)($_POST["image_id"]) :NULL;
$album_id = isset($_POST["album_id"]) ? (int)($_POST["album_id"]) :NULL; 

if(!empty($image_id) && !empty($album_id)) {
include "connectToDB.php";
$stmt = $conn->prepare("INSERT INTO album_image WHERE image_id = ? AND album_id = ?");
$stmt->bind_param('ii', $image_id, $album_id); 

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