
<?php
function checkunique($username, $email) {
    include "connectToDB.php"; 
   
    $sql = "SELECT *
    FROM users 
    WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $errors = [];
    if ($result->num_rows > 0) {
        if ($row = $result->fetch_assoc()) {
            if ($row['username'] == $username) {
                $errors['username'] = "Username already exist.";
            }
            if ($row['email'] == $email) {
                $errors['email'] = "Email already exist.";
            }
        }
        
    }

    return $errors;
}

function insertNewUser($username, $email, $password, $bio) {
    include "connectToDB.php"; 

    $hashed_password = password_hash($password, PASSWORD_DEFAULT); 
    $unique_id = uniqid();

   
    $sql = "INSERT INTO users (user_id,username, email, password, bio)
    VALUES (?,?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss",$unique_id ,$username, $email, $hashed_password, $bio);

 
    if (!$stmt->execute()) { 
        $stmt->close();
        return false; 
    }
        
    if(!isset($_SESSION)) session_start();
    $_SESSION["username"] = $username;



        //creating for the user the All album 
        include "insertAlbum.php"; 
        createAlbum('All','',$unique_id); 

        $_SESSION["user_id"] = $unique_id;
        $stmt->close();
        header("Location: index.php");
        return true;


}



?>
