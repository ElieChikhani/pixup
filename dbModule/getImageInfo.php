<?php

$image_id = isset($_GET['image_id']) ? (int)$_GET['image_id'] : null;
$user_id= isset($_GET['current_user']) ? (int)$_GET['current_user'] : null;
$username= isset($_GET['current_username']) ? $_GET['current_username'] : null;


   
// INFORMATION THAT SPECIFY IF THE USER CAN SAVE OR UNSAVE A PICTURE

if(!empty($image_id)&&!empty($user_id)){
        include 'connectToDB.php';
    
        $image_sql = "SELECT image_id, title, path, description, username, upload_date, savedCount 
                      FROM images i 
                      JOIN users u ON i.user_id = u.user_id 
                      WHERE image_id = ?";
        $stmt = $conn->prepare($image_sql);
        $stmt->bind_param("i", $image_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Getting the image infos
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
    
            $image_info = array();
            $image_info['image_id'] = $row['image_id'];
            $image_info['title'] = $row['title'];
            $image_info['path'] = $row['path'];
            $image_info['description'] = $row['description'];
            $image_info['username'] = $row['username'];
            $image_info['tags'] = array();
            $image_info['savedCount'] = $row['savedCount'];
            $image_info['upload_date'] = $row['upload_date'];
    
            // Prepared statement 
            $category_sql = "SELECT category FROM category_image WHERE image_id = ?";
            $category_stmt = $conn->prepare($category_sql);
            $category_stmt->bind_param("i", $image_id);
            $category_stmt->execute();
            $category_result = $category_stmt->get_result();
    
            while ($category_row = $category_result->fetch_assoc()) {
                $image_info['tags'][] = $category_row['category'];
            }
            $category_stmt->close();
    
    
         

                // Checking if the current user is the owner of the picture
                if ($username === $image_info['username']) {
                    $image_info['isOwner'] = true;
                } else {
                    // Prepared statement for checking if saved by current user
                    $image_info['isOwner'] = false;
                    $saved_sql = "SELECT * FROM save_image WHERE image_id = ? AND user_id = ?";
                    $saved_stmt = $conn->prepare($saved_sql);
                    $saved_stmt->bind_param("ii", $image_id, $user_id);
                    $saved_stmt->execute();
                    $saved_result = $saved_stmt->get_result();
    
                    if ($saved_result && $saved_result->num_rows > 0) {
                        $image_info['saved'] = true;
                    } else {
                        $image_info['saved'] = false;
                    }
                    $saved_stmt->close();
                }
    
            $response = [
                "success" => true,
                "data" => $image_info
            ];

        } else {
            $response = [
                "success" => false
            ];
        }
    
        $stmt->close();
        $conn->close();
    

    }else {
        $response = [
            "success" => false,
            "message" => 'cannot detect enough info'
        ]; 
    }


    header("Content-Type: application/json");
     // Send JSON response
     echo json_encode($response);
    
?>