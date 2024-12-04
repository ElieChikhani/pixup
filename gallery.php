<!DOCTYPE html>
<html lang="en">
<head>
    <title>PixUp</title>
  
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" /> 

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link href="styles/generalStyle.css" rel="stylesheet" />
    <link href="styles/galleryStyle.css" rel="stylesheet" />
</head>
<body>

<?php 
if(!isset($_SESSION)) {
    session_start();
}

$user_id = $_SESSION['user_id']; 



include "components/header.php"; 

if(isset($_GET['message'])&&isset($_GET['success'])&&!empty($_GET['message'])&&!empty($_GET['message'])) {
    $success=$_GET['success'];
    $message=$_GET['message'];

    $type=$success?'success':'danger'; 

    echo " <div class='alert alert-$type alert-dismissible fade show alert-display' role='alert'>
        $message
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
         </div>
    ";
   
}



include "components/signinrequired.php"; 


?>

<div class="p-5 mb-4 gallery-title">
            <div class="container-fluid py-5">
                <h1 class="display-5 fw-bold">Your gallery</h1>
                <p class="col-md-8 fs-4">
                   Your photography wall... 
                </p>
            </div>
        </div>


<main class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-4">Your Albums</h1>
        <a href="createAlbum.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Album  
        </a>
    </div>

    <?php include "components/albumGallery.php" ?>

    
</main>



<!-- Bootstrap JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
      integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
      crossorigin="anonymous"></script>

<script src="scripts/gallery.js"></script>

</body>
</html>
