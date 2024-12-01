<?php
session_start();
include '../dbModule/connectToDB.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: ../index.php");
    exit();
}

$albumId = intval($_GET['id']);
$userId = $_SESSION['user_id']; 

$sql = "SELECT * FROM albums WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $albumId, $userId);
$stmt->execute();
$result = $stmt->get_result();
$album = $result->fetch_assoc();

if (!$album) {
    header("Location: ../gallery.php?error=Album not found or you do not have permission to edit it.");
    exit();
}

$sqlImages = "SELECT id, image_path FROM images WHERE album_id = ?";
$stmtImages = $conn->prepare($sqlImages);
$stmtImages->bind_param("i", $albumId);
$stmtImages->execute();
$resultImages = $stmtImages->get_result();
$images = [];
while ($row = $resultImages->fetch_assoc()) {
    $images[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);

    $sql = "UPDATE albums SET name = ?, description = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $name, $description, $albumId);
    $stmt->execute();

    if (isset($_FILES['images']) && $_FILES['images']['error'][0] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
            $imageName = basename($_FILES['images']['name'][$index]);
            $imageDestination = '../uploads/' . $imageName;
            $imageType = mime_content_type($tmpName);

            if (in_array($imageType, $allowedTypes)) {
                $sqlCheck = "SELECT id FROM images WHERE album_id = ? AND image_path = ?";
                $stmtCheck = $conn->prepare($sqlCheck);
                $stmtCheck->bind_param("is", $albumId, $imageDestination);
                $stmtCheck->execute();
                $resultCheck = $stmtCheck->get_result();

                if ($resultCheck->num_rows == 0) {
                    if (move_uploaded_file($tmpName, $imageDestination)) {
                        $sqlInsert = "INSERT INTO images (album_id, image_path) VALUES (?, ?)";
                        $stmtInsert = $conn->prepare($sqlInsert);
                        $stmtInsert->bind_param("is", $albumId, $imageDestination);
                        $stmtInsert->execute();
                    }
                }
            } else {
                echo "Invalid file type: $imageName. Only JPG, PNG, and GIF files are allowed.";
            }
        }
    }

    // Update album thumbnail latest image uploaded
    $sqlGetLatestImage = "SELECT image_path FROM images WHERE album_id = ? ORDER BY id DESC LIMIT 1";
    $stmtGetLatestImage = $conn->prepare($sqlGetLatestImage);
    $stmtGetLatestImage->bind_param("i", $albumId);
    $stmtGetLatestImage->execute();
    $resultLatestImage = $stmtGetLatestImage->get_result();
    $latestImage = $resultLatestImage->fetch_assoc();

    if ($latestImage) {
        $sqlUpdateThumbnail = "UPDATE albums SET thumbnail = ? WHERE id = ?";
        $stmtUpdateThumbnail = $conn->prepare($sqlUpdateThumbnail);
        $stmtUpdateThumbnail->bind_param("si", $latestImage['image_path'], $albumId);
        $stmtUpdateThumbnail->execute();
    }

    if (isset($_POST['remove_images'])) {
        $removeImageIds = $_POST['remove_images'];
        foreach ($removeImageIds as $imageId) {
            $sqlDelete = "DELETE FROM images WHERE id = ? AND album_id = ?";
            $stmtDelete = $conn->prepare($sqlDelete);
            $stmtDelete->bind_param("ii", $imageId, $albumId);
            $stmtDelete->execute();
        }
    }

    header("Location: editAlbum.php?id=$albumId&success=Album updated successfully!");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Album</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" 
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <link href="styles/generalStyle.css" rel="stylesheet" />
</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">PixUp</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../gallery.php">Back to Gallery</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<main class="container py-5">
    <h1 class="display-4 mb-4">Edit Album</h1>

    <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
        <div class="mb-3">
            <label for="name" class="form-label">Album Title</label>
            <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($album['name']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($album['description']); ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Current Thumbnail</label>
            <?php if ($album['thumbnail']): ?>
                <div>
                    <img src="<?php echo htmlspecialchars($album['thumbnail']); ?>" alt="Album Thumbnail" class="img-fluid" style="max-width: 150px; max-height: 150px;">
                </div>
            <?php else: ?>
                <p>No thumbnail set.</p>
            <?php endif; ?>
        </div>

        <h2>Manage Images</h2>
        <div class="row">
            <?php foreach ($images as $image): ?>
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($image['image_path']); ?>" class="card-img-top" alt="Image">
                        <div class="card-body">
                            <label>
                                <input type="checkbox" name="remove_images[]" value="<?php echo $image['id']; ?>"> Remove
                            </label>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mb-3">
            <label for="images" class="form-label">Upload New Images</label>
            <input type="file" id="images" name="images[]" class="form-control" multiple>
            <div id="uploadError" class="text-danger mt-2" style="display: none;">Only JPG, PNG, and GIF files are allowed!</div>
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
        <form method="POST" action="deleteAlbum.php" onsubmit="return confirm('Are you sure you want to delete this album?');">
            <input type="hidden" name="album_id" value="<?php echo $albumId; ?>">
            <button type="submit" class="btn btn-danger">Delete Album</button>
        </form>
    </form>
</main>

<!-- Bootstrap JS (Popper.js and Bootstrap) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-FxS37m4rMfRfxK84bHGJl5zzQs5w1Rzwpdhw9wZ9f48yjk48SK0UyYxeO5bFs2f4X" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-cuF7rFFchd9Q4DmdsvsL8pMOFgOU9yH9lcwF7JlL4tvzpzXZHGn5n5qY8j+zpsrh" crossorigin="anonymous"></script>

<script>
    document.getElementById('images').addEventListener('change', function (event) {
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        const files = event.target.files;
        let valid = true;
        for (let i = 0; i < files.length; i++) {
            if (!allowedTypes.includes(files[i].type)) {
                valid = false;
                break;
            }
        }
        if (!valid) {
            document.getElementById('uploadError').style.display = 'block';
        } else {
            document.getElementById('uploadError').style.display = 'none';
        }
    });

    const removeCheckboxes = document.querySelectorAll('input[type="checkbox"][name="remove_images[]"]');
    removeCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            if (this.checked) {
                const confirmRemove = confirm('Are you sure you want to remove this image?');
                if (!confirmRemove) {
                    this.checked = false;
                }
            }
        });
    });
</script>

</body>
</html>
