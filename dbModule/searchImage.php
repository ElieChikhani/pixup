
<?php

/**
 * This executes any kind of searching and returns the result in a JSON file.
 */

// Connect to the database
include 'connectToDB.php'; 

// Query parts
$sql = "SELECT * FROM images i";
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
    $conditions[] = "c.category LIKE ?";
    $types .= "s";
    $values[] = '%'.$category.'%';
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
    $orderBy = " ORDER BY i.image_id DESC";
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
    $images[$row["image_id"]] = $row["path"];
}

// Prepare the response
if ($result->num_rows === 0) {
    $response = [
        "success" => false,
        "message" => "No results found."
    ];
} else {
    $response = [
        "success" => true,
        "data" => $images,
        "numberOfImages" => $result->num_rows
    ];
}

// Close resources
$stmt->close();
$conn->close();

// Send JSON response
header("Content-Type: application/json");
echo json_encode($response);
