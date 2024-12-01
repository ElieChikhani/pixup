
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
$album_id_not = isset($_GET['album_id_not']) ? (int)$_GET['album_id_not'] : null;
$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;
$saved = isset($_GET['saved']) ? (bool)$_GET['saved'] : false; //must come with userID
$keyword = isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : null;
$order = isset($_GET['order']) ? trim(htmlspecialchars($_GET['order'])) : 'recent';
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : null;
$current_page = isset($_GET['current_page']) ? (int)$_GET['current_page'] : null;
$filter=isset($_GET['filter']) ? htmlspecialchars($_GET['filter']) : null;


$categoryJoin="";
$savedJoin="";
$albumJoin=""; 


if (!empty($keyword) && empty($filter)) {
    //getting result on keyword only 
    $categoryJoin = " NATURAL JOIN category_image c ";
    $conditions[] = "(c.category LIKE ? OR i.title LIKE ? OR i.description LIKE ?)";
    $types .= "sss"; // 3 strings

   
    for ($i = 0; $i < 3; $i++) {
        $values[] = '%' . $keyword . '%';
    }
} elseif (!empty($filter) && empty($keyword)) {
    //getting filter result on vategory only
    $categoryJoin = " NATURAL JOIN category_image c ";
    $conditions[] = "(c.category LIKE ?)";
    $types .= "s"; // 1 string

  
    $values[] = $filter;

} elseif (!empty($filter) && !empty($keyword)) { //done sepeartly to adjust brackets. 
    //getting keyword result WITHIN THE CATEGORY FILTER
    $categoryJoin = " NATURAL JOIN category_image c ";
    $conditions[] = "(c.category = ? AND (i.title LIKE ? OR i.description LIKE ?))";
    $types .= "sss"; 

    
    $values[] = $filter;
    for ($i = 0; $i < 2; $i++) {
        $values[] = '%' . $keyword . '%';
    }
}

if (!empty($album_id)) {
    $albumJoin = " NATURAL JOIN album_image a ";
    $conditions[] = "a.album_id = ?";
    $types .= "i";
    $values[] = $album_id;
}

if (!empty($album_id_not)) {
    $conditions[] = "i.image_id NOT IN (SELECT image_id FROM album_image WHERE album_id = ?)";
    $types .= "i";
    $values[] = $album_id_not;
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
if ($order == 'recent') {
    $orderBy = " ORDER BY i.image_id DESC";
} elseif($order == 'popular') {
    $orderBy = " ORDER BY savedCount DESC";
}
$sql .= $orderBy;

if(!empty($limit) && !empty($current_page)){
    $offset = ($current_page - 1) * $limit; 
    $sql .= " LIMIT $limit OFFSET $offset"; 
}

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
    $images[] = [
        "id" => $row["image_id"],
        "path" => $row["path"]
    ];
}

// Prepare the response
if ($result->num_rows === 0) {
    $response = [
        "success" => false,
        "message" => "No results found.",
        "sql" => $sql
     
    ];
} else {
    $response = [
        "success" => true,
        "images" => $images,
        "result_number" => $result->num_rows
    ];
}

// Close resources
$stmt->close();
$conn->close();

// Send JSON response
header("Content-Type: application/json");
echo json_encode($response);
