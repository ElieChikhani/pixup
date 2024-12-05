<?php


//all the info of a user (except the password)
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

include 'connectToDB.php';


$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$user_info = array(); 

if ($stmt) {
    $stmt->bind_param("s", $user_id); 
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $user_info['user_id']=$row['user_id'];
            $user_info['username']=$row['username'];
            $user_info['email']=$row['email'];
            $user_info['bio']=$row['bio'];
            $user_info['creation_date']=$row['creation_date'];
            $user_info['imageCount']=$row['imageCount'];
            $user_info['albumCount']=$row['albumCount'];
        }

        $response = [
            'success' => true,
            'user_info' => $user_info,
            'numberOfResults'=> $result->num_rows
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'no user found'
        ];
    }
    
    $stmt->close();
} else {
    $response = [
        'success' => false,
        'message' => 'sql error'
    ]; 
}

$conn->close();
header('Content-Type: application/json');
echo json_encode($response);

?>