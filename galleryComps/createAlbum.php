<?php
session_start();
include '../dbModule/connectToDB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    
    if (empty($name) || empty($description)) {
        $error = "Album name and description are required.";
    } else {
        $sql = "INSERT INTO albums (name, description, created_at) VALUES (?, ?, CURRENT_TIMESTAMP)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $name, $description);

        if ($stmt->execute()) {
            header("Location: gallery.php?success=Album created successfully!");
            exit();
        } else {
            $error = "Failed to create album. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Album</title>
    <link rel="stylesheet" href="styles/generalStyle.css">
</head>
<body>
    <h1>Create Album</h1>
    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
    <form method="POST">
        <label for="name">Album Title:</label>
        <input type="text" id="name" name="name" required><br><br>
        
        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" required></textarea><br><br>
        
        <button type="submit">Create Album</button>
    </form>
</body>
</html>
