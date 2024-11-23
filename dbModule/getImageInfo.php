<?php

// Connect to the database


include 'connectToDB.php';

$image_id = isset($_GET['image_id']) ? (int)$_GET['image_id'] : null;

if(!empty($image_id)){
    $image_sql = "SELECT image_id, title, path, description, username, upload_date, savedCount FROM images i JOIN users u ON i.user_id = u.user_id WHERE image_id = $image_id";

    $result = $conn->query($image_sql); 


    //getting the image infos
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


         //getting the list of categories 
        $category_sql = "SELECT category FROM category_image WHERE image_id=$image_id";
        $result = $conn->query($category_sql); 

        while ($row = $result->fetch_assoc()) {
            $image_info['tags'][] = $row['category'] ;
        }


        //INFORMATION THAT SPECIFIY IF THE USER CAN SAVE OR UNSAVE A PICTURE 


        $user_id=1; //to be changed
        $username = 'eliechikhani';//to be changed

        if(!empty($user_id)){
        $image_info['isLoggedIn']=true; 
        //check if the current user is the owner of the picture
        if($username == $image_info['username'] ){
            $image_info['isOwner'] = true;
        }else {
            //checked if saved by current user 
         $saved_sql = "SELECT * FROM save_image WHERE image_id = $image_id AND user_id = $user_id"; 
         $result = $conn->query($saved_sql);

            if ($result && $result->num_rows > 0) {
            $image_info['saved'] = true;
            }else {
            $image_info['saved'] = false ;
            }

        }
    }else {
        $image_info['isLoggedIn']=false; 
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

  

    // Send JSON response
    header("Content-Type: application/json");
    echo json_encode($response);

}



$conn->close();

?>