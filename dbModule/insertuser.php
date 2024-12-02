
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

   
    $sql = "INSERT INTO users (username, email, password, bio)
    VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $bio);

 
    if ($stmt->execute()) {
        return true; 
    } else {
        return false; 
    }

    $stmt->close();
}



?>
