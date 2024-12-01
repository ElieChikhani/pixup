<?php
session_start();
include '../dbModule/connectToDB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id']; 
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    
    if (empty($name) || empty($description)) {
        $error = "Album name and description are required.";
    } else {
        $originalName = $name;
        $counter = 0;

        do {
            $check_sql = "SELECT COUNT(*) as count FROM albums WHERE user_id = ? AND name = ?";
            $stmt_check = $conn->prepare($check_sql);
            $stmt_check->bind_param("is", $userId, $name);
            $stmt_check->execute();
            $result = $stmt_check->get_result();
            $row = $result->fetch_assoc();

            if ($row['count'] > 0) {
                $counter++;
                $name = $originalName . " ($counter)";
            } else {
                break;
            }
        } while (true);

        $sql = "INSERT INTO albums (user_id, name, description, created_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $userId, $name, $description);

        if ($stmt->execute()) {
            header("Location: ../gallery.php?success=Album created successfully!");
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
    <title>Create Album - PixUp</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" /> 

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="styles/generalStyle.css" rel="stylesheet" />
    <link href="styles/indexStyle.css" rel="stylesheet" />

    <style>
        .btn-submit { background-color: #0056b3; color: white; border: none; }
        .btn-submit:hover { background-color: #003d7a; }
        .btn-cancel { background-color: #f8d7da; color: black; }
        .btn-cancel:hover { background-color: #f5c6cb; }
        .error-message { color: red; font-weight: bold; }
    </style>
</head>
<body>
<header></header>

<main class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-4">Create Your Album</h1>
        <a href="../gallery.php" class="btn btn-outline-primary">Back to Gallery</a>
    </div>

    <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>

    <form method="POST" class="bg-light p-4 rounded shadow">
        <div class="mb-3">
            <label for="name" class="form-label">Album Title:</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description:</label>
            <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-submit">Create Album</button>
            <a href="../gallery.php" class="btn btn-cancel">Cancel</a>
        </div>
    </form>
</main>

<!-- Bootstrap JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

</body>
</html>
