<?php 
$email_or_username = isset($_POST['email_or_username']) ? trim(htmlspecialchars($_POST['email_or_username'])): null;
$password = isset($_POST['password']) ? trim(htmlspecialchars($_POST['password'])): null;


//check email_or_username

include "connectToDB.php";

$sql = "SELECT *
FROM users
WHERE username = ? OR email= ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss",$email_or_username,$email_or_username);
$stmt->execute();
$result = $stmt->get_result();

if(!$result){
    $message="can not sign in at the moment";
    header("Location: ../signin.php?message=$message");

}else {
    if($result->num_rows==0){
        $message="username or email incorrect";
        header("Location: ../signin.php?message=$message");

    }else{
        $row=result->fetch_assoc();
        if($row["password"]==$password){
            if(!isset($_SESSION)) session_start();

            $_SESSION['user_id']=$row['user_id'];
            $_SESSION['username']=$row['username'];
            header("Location: ../index.php");
            
        }else{
            $message="incorrect password";
            header("Location: ../signin.php?message=$message");

        }
    }
}


?>