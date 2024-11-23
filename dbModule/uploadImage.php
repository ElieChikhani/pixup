<?php 

/**
 * Uploading an image consists of : 
 *   - adding the image in the database 
 *   - adding the image in the All album and other albums if necessary. 
 *   - fetching the categories from the API
 *   - adding the categories to the images 
 */

 //user id from session
 $user_id = 1;

    if($_SERVER['REQUEST_METHOD'] === "POST") {
        $title=htmlspecialchars($_POST['image-title']);
        $description=htmlspecialchars($_POST['image-description']);

        if(isset($_POST['albums'])){
            $albums = $_POST['albums'];
        }

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileType = $_FILES['image']['type'];

            //file extension
            $fileNameComponents = pathinfo($fileName);
            $fileExtension = $fileNameComponents['extension'];
        }


       include 'connectToDB.php'; 

       
        $sql = "INSERT INTO images(title,description) VALUES('$title','$description')";

        if (!($conn->query($sql) === TRUE)) {
            echo "Error inserting database: " . $conn->error;
        }

        //getting the id of the just inserted image : 
        $sql = "SELECT image_id FROM images ORDER BY image_id DESC LIMIT 1";
        $result = $conn->query($sql); // Execute the query

        if ($result && $result->num_rows > 0) {
           
            $row = $result->fetch_assoc();
            $image_id = $row['image_id']; 
            echo "The ID of the just-inserted image is: " . $image_id;
        } else {
            echo "Error fetching the image ID or no matching rows.";
        }

        $path = "images/".$image_id.".".$fileExtension; 
        

        //adding the path
        $sql = "UPDATE images SET path = '$path' WHERE image_id = '$image_id'"; 
        if (!($conn->query($sql) === TRUE)) {
            echo "Error updating database: " . $conn->error;
        }

        //insert in the ALL album 
        $sql ="SELECT album_id FROM albums WHERE user_id = $user_id AND album_name = 'All' LIMIT 1"; 
        $result = $conn->query($sql); 
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $all_id = $row['album_id'];
            $sql = "INSERT INTO album_image(image_id,album_id) VALUES($image_id,$all_id)";
            if (!($conn->query($sql) === TRUE)) {
                echo "Error inserting into ALL album: " . $conn->error;
            }; 
        }

        
        //insert picture in albums selected by user 
        if(isset($albums)){
            foreach ($albums as $album_id) {
                $sql = "INSERT INTO album_image(image_id,album_id) VALUES($image_id,$album_id)";
                if (!($conn->query($sql) === TRUE)) {
                    echo "Error inserting into ALL album: " . $conn->error;
                }; 
            }
        }


        //add the image to the directory : 
        $uploadDir = '../images/';
       
        $fileExtension = $fileNameComponents['extension'];
        $newFileName = $image_id . '.' . $fileExtension;

        //target path
        $destPath = $uploadDir . $newFileName;

        // Move the file to the target directory
        if (move_uploaded_file($fileTmpPath, $destPath)) {
            echo "Image uploaded successfully!<br>";
            echo "File path: " . htmlspecialchars($destPath);
        } else {
            echo "Error moving the uploaded file.";
        }
        
    }else {
        echo "No file uploaded or there was an upload error.";
    }

    
    $conn->close();

?>