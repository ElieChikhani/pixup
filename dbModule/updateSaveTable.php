<?php
  header("Content-Type: application/json");
if(!isset($_SESSION)) session_start(); 
$user_id =$_SESSION['user_id']; 
$image_id=(isset($_SESSION) && isset($_SESSION['current_image_id']))?$_SESSION['current_image_id']:null; 


if (!empty($image_id)) {

    include 'connectToDB.php';

    $saved = false;

     // Checking that the image does NOT beling to the user
     $stmt = $conn->prepare('SELECT * FROM images WHERE image_id = ? AND user_id != ?');
    $stmt->bind_param('ss', $image_id, $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $image = $result->fetch_assoc();

    if (!$image) {
    echo json_encode(['success' => false, 'message' => 'Action not authorized']);
    exit;
    }

    
    // Checking if the image is already saved by the user
    $check_sql = "SELECT * FROM save_image WHERE image_id = ? AND user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ss", $image_id, $user_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        $saved = true;
    }
    $check_stmt->close();
    
  
    if (!$saved) {
        // Inserting into save_image
        $save_sql = "INSERT INTO save_image(image_id, user_id) VALUES (?, ?)";
        $save_stmt = $conn->prepare($save_sql);
        $save_stmt->bind_param("ss", $image_id, $user_id);
    
        // Updating savedCount
        $update_sql = "UPDATE images SET savedCount = savedCount + 1 WHERE image_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("s", $image_id);
    
        $action = 'saved';
    } else {
        // Deleting from save_image
        $save_sql = "DELETE FROM save_image WHERE image_id = ? AND user_id = ?";
        $save_stmt = $conn->prepare($save_sql);
        $save_stmt->bind_param("ss", $image_id, $user_id);
    
        // Updating savedCount
        $update_sql = "UPDATE images SET savedCount = savedCount - 1 WHERE image_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("s", $image_id);
    
        $action = 'unsaved';
    }
    
    // Execution
    if ($save_stmt->execute() && $update_stmt->execute() && $conn->affected_rows > 0) {
        $response = [
            'success' => true,
            'action' => $action
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'could not reflect action in database'
        ];
    }

    $save_stmt->close();
    $update_stmt->close();
    $conn->close();

}else {
    $response = [
        'success' => false,
        'message' => 'image id not found'
    ];

}


  
    echo json_encode($response);
    

?>