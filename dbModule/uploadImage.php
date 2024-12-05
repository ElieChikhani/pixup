<?php 

/**
 * Uploading an image consists of : 
 *   - adding the image in the database 
 *   - adding the image in the All album and other albums if necessary. 
 *   - fetching the categories from the API
 *   - adding the categories to the images 
 */

 //user id from session
 if(!isset($_SESSION)) session_start(); 
 $user_id =$_SESSION['user_id']; 

 $success = true; 
 $message = 'Your image is uploaded';
 

 if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $title = htmlspecialchars($_POST['image-title']);
    $description = htmlspecialchars($_POST['image-description']);

    if (isset($_POST['albums'])) {
        $albums = $_POST['albums'];
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileSize = $_FILES['image']['size'];
        $fileType = $_FILES['image']['type'];

        // File extension
        $fileNameComponents = pathinfo($fileName);
        $fileExtension = $fileNameComponents['extension'];
    }

    include 'connectToDB.php';

    $unique_id = uniqid();
    $path = 'images/'.$unique_id.'.'.$fileExtension; 

    // Insert into images table
    $insertImageSql = "INSERT INTO images(image_id,title, description, user_id,path) VALUES (?,?, ?, ?,?)";
    $stmt = $conn->prepare($insertImageSql);
    $stmt->bind_param("sssss",$unique_id, $title, $description, $user_id,$path);

    if (!$stmt->execute()) {
        $success=false; 
        $message = "Couldn't upload image try again later";
        exit;
    }
    $stmt->close();

    $image_id = $unique_id;


    // Insert into the "All" album
    $getAllAlbumSql = "SELECT album_id FROM albums WHERE user_id = ? AND album_name = 'All' LIMIT 1";
    $stmt = $conn->prepare($getAllAlbumSql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $all_id = $row['album_id'];

        $insertAlbumImageSql = "INSERT INTO album_image(image_id, album_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insertAlbumImageSql);
        $stmt->bind_param("ss", $image_id, $all_id);

        if (!$stmt->execute()) {
            $success=false; 
            $message = "Couldn't insert image into your desired albums";
        }
        $stmt->close();
    }

    // Insert image into albums selected by user
    if (isset($albums)) {
        foreach ($albums as $album_id) {
            $stmt = $conn->prepare("INSERT INTO album_image(image_id, album_id) VALUES (?, ?)");
            $stmt->bind_param("ss", $image_id, $album_id);

            if (!$stmt->execute()) {
                $success=false; 
                $message = "Couldn't insert image into your desired albums";
            }
            $stmt->close();
        }
    }

    // Increment the image count for the user
    $incrementImageCountSql = "UPDATE users SET imageCount = imageCount + 1 WHERE user_id = ?";
    $stmt = $conn->prepare($incrementImageCountSql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->close();
    

    // Add the image to the directory
    $uploadDir = '../images/';
    $newFileName = $image_id . '.' . $fileExtension;
    $destPath = $uploadDir . $newFileName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
     // Categorize the image
       include 'categoriesImage.php';
    } else {
       $success=false; 
       $message = "Coun't categorize the image !";
    }

} else {
    echo "No file uploaded or there was an upload error.";
}


    
    $conn->close();
    
    header("Location: ../index.php?success=$success&message=$message");
    exit(); 

?>