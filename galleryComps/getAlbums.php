<?php
include 'connectToDB.php';

$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;

if (!empty($user_id)) {
    $sql = "
    SELECT album_id, album_name, path
    FROM albums a
    JOIN album_image ai ON a.album_id = ai.album_id
    JOIN images i ON ai.image_id = i.image_id
    WHERE a.user_id = ? AND ai.album_image_date = (
        SELECT MAX(ai2.album_image_date)
        FROM album_image ai2
        WHERE ai2.album_id = ai.album_id
    )";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $albums = [];

    while ($row = $result->fetch_assoc()) {
        $albums[] = [
            'album_id' => $row['album_id'],
            'album_name' => $row['album_name'],
            'recent_image_path' => $row['path']
        ];
    }

    if (count($albums) > 0) {
        $response = [
            'status' => 'success',
            'albums' => $albums
        ];
    } else {
        $response = [
            'status' => 'failed',
            'message' => 'No albums found'
        ];
    }
} else {
    $response = [
        'status' => 'failed',
        'message' => 'No user ID provided'
    ];
}

$conn->close();

header("Content-Type: application/json");
echo json_encode($response);
?>
