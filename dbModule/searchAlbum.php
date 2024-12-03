<?php
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

include 'connectToDB.php';

if (!empty($user_id)) {
   
    $sql = "SELECT a.album_id, a.album_name, i.path 
    FROM albums a 
    LEFT JOIN album_image ai ON a.album_id = ai.album_id 
    LEFT JOIN images i ON ai.image_id = i.image_id 
    WHERE a.user_id = ? 
    AND ( ai.album_image_id = ( SELECT MAX(ai2.album_image_id) FROM album_image ai2 WHERE ai2.album_id = a.album_id) 
    OR ai.album_image_id IS NULL)
    ORDER BY a.album_creation_date";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id); 
    

    $stmt->execute();


    $result = $stmt->get_result();
    $albums = array();

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $albums[] = [
                'album_id' => $row['album_id'],
                'album_name' => $row['album_name'],
                'recent_image_path' => $row['path']
            ];
        }

        $response = [
            'success' => true,
            'albums' => $albums,
            'result_number' => $result->num_rows
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Could not fetch from the database.'
        ];
    }

    $stmt->close(); 
} else {
    $response = [
        'success' => false,
        'message' => 'No user ID provided.'
    ];
}

// Send JSON response
header("Content-Type: application/json");
echo json_encode($response);

$conn->close(); 



?>