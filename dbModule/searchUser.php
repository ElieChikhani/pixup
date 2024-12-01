<?php

include 'connectToDB.php'; 

$search_term = isset($_GET['search_term']) ? trim(htmlspecialchars($_GET['search_term'])):'';
$limit = isset($_GET['limit']) ? (int)$_GET['limit']:10; 
$current_page = isset($_GET['current_page']) ? (int)$_GET['current_page'] :1;


$offset = ($current_page -1) * $limit;

$sql = "SELECT username, user_id, imageCount FROM users"; 
$types ='';
$values = array();

if(!empty($search_term)){
    $sql .= " WHERE username LIKE ? ";
    $values[] = "%$search_term%"; 
    $types .= 's'; 
}

$sql .= " LIMIT ? OFFSET ?"; 
$values[] = $limit; 
$values[] = $offset; 
$types .= 'ii';

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$values); 
$stmt->execute();
$result = $stmt->get_result();

if( $result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $username = $row['username'];
        $imageCount = $row['imageCount'];
        $user_id = $row['user_id'];

        $users[] = [
            'user_id' => $user_id,
            'username'=> $username,
            'imageCount'=> $imageCount
        ]; 
    }

    $response = [
        'success' => true,
        'users'=> $users,
    ]; 
}else {

    $response = [
        'success' => false,
        'message'=> "users not found",
    ]; 

}


header("Content-Type: application/json");
echo json_encode($response);








?>