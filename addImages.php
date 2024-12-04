<!DOCTYPE html>
<html lang="en">

<?php 

if(!isset($_SESSION)) session_start();

$user_id = $_SESSION["user_id"]; 
$album_id = isset($_GET['id'])?$_GET['id']:NULL;
$album_name = isset($_GET['album_name'])?trim(htmlspecialchars($_GET['album_name'])):NULL;


include "components/canEditAlbum.php";

if(!canEditAlbum($album_id,$user_id)){
    $message = "Not authorized to edit this album right now";
    $success = false; 
    header("Location: index.php?success=$success&message=$message");
    exit(); 
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Images</title>

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link href="styles/generalStyle.css" rel="stylesheet" />
    <link href="styles/editAlbum.css" rel="stylesheet" />
</head>

<body>

     <style>
        .photo-gallery {
            overflow-y: scroll;
            min-height:80%; 
            max-height:80%; 
        }


     </style>


    <main class="container py-2 mb-3 mt-3" style="border-radius:10px; height:95vh">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="display-7 mb-2"> Select the images you want to add to <?php echo$album_name ?> </h3>
            <a id="done-button" class="btn btn-primary" href="editAlbum.php?id=<?php echo  urlencode(htmlspecialchars($album_id));?>">Done</a>
        </div>
        

        <form id='search-form' selectable = true album_id_not=<?php echo htmlspecialchars($album_id); ?> user_id=<?php echo htmlspecialchars($user_id); ?> style="background-color: white;">

            <input id='search-bar' class='form-control me-sm-2' type='text' placeholder='Search for photos'
                name='search' />

            <select class='form-select form-select-lg' name='order' id='order-select'>
                <option value='recent'>Recent</option>
                <option value='popular'>Most Popular</option>
            </select>

        </form>

        <?php include "components/imageGrid.php" ?>
        
    </main>



    <!--Masonry.js for grid layout-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/4.2.2/masonry.pkgd.min.js"></script>

    <!--check the loading state of an image-->
    <script src="https://unpkg.com/imagesloaded/imagesloaded.pkgd.min.js"></script>


    <!-- Bootstrap JS (Popper.js and Bootstrap) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>

    <script src="scripts/allGridScript.js"></script>

    
    <script src="scripts/addImages.js"></script>


</body>

</html>