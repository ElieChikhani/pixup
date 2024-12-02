<?php 

function checkCredentials($email_or_username, $password){
    include "connectToDB.php";

    $errors = []; 

    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email_or_username, $email_or_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        $errors['general_error'] = "Cannot sign in at the moment, please try again later"; 
    } else {
        if ($result->num_rows == 0) {
            $errors['email_or_username'] = "Username or email does not exist"; 
        } else {
            $row = $result->fetch_assoc();

            if (password_verify($password, $row["password"])) {
                if (!isset($_SESSION)) session_start();

                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['username'] = $row['username'];
                header("Location: index.php");
                exit(); 
            } else {
                $errors['password'] = "Password incorrect";
            }
        }
    }

    return $errors; 
}
?>
