
<?php

/**
 * This executes any kind of searching and returns the result in a JSON file.
 */

// Connect to the database
$host = 'localhost';
$db = 'pixup';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query parts
$sql = "SELECT i.image_id FROM images i";
$categoryJoin = '';
$conditions = [];
$types = ""; // parameters type
$values = []; // parameter values

// From where the data is retrieved
$album_id = isset($_GET['album_id']) ? (int)$_GET['album_id'] : null;
$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;
$saved = isset($_GET['saved']) ? (bool)$_GET['saved'] : false; //must come with userID
$category = isset($_GET['category']) ? $_GET['category'] : null;
$order = isset($_GET['order']) ? $_GET['order'] : 'recent';
$categoryJoin="";
$savedJoin="";
$albumJoin=""; 

if (!empty($category)) {
    $categoryJoin = " NATURAL JOIN category_image c ";
    $conditions[] = "c.category = ?";
    $types .= "s";
    $values[] = $category;
}

if (!empty($album_id)) {
    $albumJoin = " NATURAL JOIN album_image a ";
    $conditions[] = "a.album_id = ?";
    $types .= "i";
    $values[] = $album_id;
}

if (!empty($user_id)) {
    if ($saved) {
        $savedJoin = " JOIN save_image s ON i.image_id = s.image_id "; //not natural because we need to precise that the join must be on the image id and NOT the user id
        $conditions[] = "s.user_id = ?"; //image saved by this user not belonging to the user
        $types .= "i";
        $values[] = $user_id;
    } else {
        $conditions[] = "i.user_id = ?";
        $types .= "i";
        $values[] = $user_id;
    }
}

// The WHERE clause
if (!empty($conditions)) {
    $sql .= $categoryJoin . $albumJoin .$savedJoin. " WHERE " . implode(" AND ", $conditions);
} else {
    $sql .= " WHERE 1";
}

//Setting the ORDER BY clause
$orderBy = '';
if ($order === 'recent') {
    $orderBy = " ORDER BY image_id DESC";
} elseif ($order === 'popular') {
    $orderBy = " ORDER BY savedCount DESC";
}
$sql .= $orderBy;

//Preparing the statement
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

//Binding parameters
if (!empty($types)) {
    $stmt->bind_param($types, ...$values);
}

//Executing and fetching the results
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}

$result = $stmt->get_result();
$images = [];
while ($row = $result->fetch_assoc()) {
    $images[] = $row;
}

// Prepare the response
if ($result->num_rows === 0) {
    $response = [
        "success" => false,
        "sql" => $sql,
        "message" => "No results found."
    ];
} else {
    $response = [
        "success" => true,
        "sql" => $sql,
        "data" => $images
    ];
}

// Close resources
$stmt->close();
$conn->close();

// Send JSON response
header("Content-Type: application/json");
echo json_encode($response);
