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

    // Insert into images table
    $insertImageSql = "INSERT INTO images(title, description, user_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertImageSql);
    $stmt->bind_param("ssi", $title, $description, $user_id);

    if (!$stmt->execute()) {
        echo "Error inserting into database: " . $stmt->error;
        exit;
    }
    $stmt->close();

    // Get the ID of the just-inserted image
    $image_id = $conn->insert_id;

    $path = "images/" . $image_id . "." . $fileExtension;

    // Update the path in the images table
    $updatePathSql = "UPDATE images SET path = ? WHERE image_id = ?";
    $stmt = $conn->prepare($updatePathSql);
    $stmt->bind_param("si", $path, $image_id);

    if (!$stmt->execute()) {
        echo "Error updating database: " . $stmt->error;
    }
    $stmt->close();

    // Insert into the "All" album
    $getAllAlbumSql = "SELECT album_id FROM albums WHERE user_id = ? AND album_name = 'All' LIMIT 1";
    $stmt = $conn->prepare($getAllAlbumSql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $all_id = $row['album_id'];

        $insertAlbumImageSql = "INSERT INTO album_image(image_id, album_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insertAlbumImageSql);
        $stmt->bind_param("ii", $image_id, $all_id);

        if (!$stmt->execute()) {
            echo "Error inserting into ALL album: " . $stmt->error;
        }
        $stmt->close();
    }

    // Insert image into albums selected by user
    if (isset($albums)) {
        foreach ($albums as $album_id) {
            $stmt = $conn->prepare("INSERT INTO album_image(image_id, album_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $image_id, $album_id);

            if (!$stmt->execute()) {
                echo "Error inserting into selected album: " . $stmt->error;
            }
            $stmt->close();
        }
    }

    // Increment the image count for the user
    $incrementImageCountSql = "UPDATE users SET imageCount = imageCount + 1 WHERE user_id = ?";
    $stmt = $conn->prepare($incrementImageCountSql);
    $stmt->bind_param("i", $user_id);

    if (!$stmt->execute()) {
        echo "Error incrementing imageCount: " . $stmt->error;
    }
    $stmt->close();

    // Add the image to the directory
    $uploadDir = '../images/';
    $newFileName = $image_id . '.' . $fileExtension;
    $destPath = $uploadDir . $newFileName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        echo "Image uploaded successfully!<br>";
        echo "File path: " . htmlspecialchars($destPath);
        
         // Categorize the image
        include 'categoriesImage.php';
    } else {
        echo "Error moving the uploaded file.";
    }

} else {
    echo "No file uploaded or there was an upload error.";
}


    
    $conn->close();

?>