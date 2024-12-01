<?php
session_start();
include '../dbModule/connectToDB.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['user_id'];
$search = isset($_GET['search']) ? '%' . htmlspecialchars($_GET['search']) . '%' : '%';
$order = isset($_GET['order']) ? $_GET['order'] : 'recent';

switch ($order) {
    case 'alphabetical':
        $orderBy = 'name ASC';
        break;
    case 'oldest':
        $orderBy = 'created_at ASC';
        break;
    case 'recent':
    default:
        $orderBy = 'created_at DESC';
        break;
}

$sql = "SELECT id, name, description, created_at, thumbnail 
        FROM albums 
        WHERE user_id = ? AND name LIKE ? 
        ORDER BY $orderBy";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $userId, $search);
$stmt->execute();
$result = $stmt->get_result();

$albums = [];
while ($row = $result->fetch_assoc()) {
    $albums[] = $row;
}

echo json_encode($albums);
?>
