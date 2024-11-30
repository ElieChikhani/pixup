<?php



$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;

include 'connectToDB.php';

if (!empty($user_id)) {
    // Base SQL query
    $sql = "SELECT album_id, album_name, path 
            FROM (albums a NATURAL JOIN album_image ai) 
            JOIN images i ON ai.image_id = i.image_id 
            WHERE a.user_id = ? 
            AND ai.album_image_date = (
                SELECT MAX(ai2.album_image_date) 
                FROM album_image ai2 
                WHERE ai2.album_id = ai.album_id
            )";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);
 $stmt->bind_param("i", $user_id); // Only user_id is used
    

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