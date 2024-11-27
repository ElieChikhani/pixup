<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albus</title>

     <!-- Bootstrap CSS v5.2.1 -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link href="styles/generalStyle.css" rel="stylesheet" />

</head>
<body>

<main>

<?php
$search = ''; 
$order ="recent"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search = isset($_POST['search']) ? $_POST['search'] : '';
    $order = isset($_POST['order']) ? $_POST['order'] : 'recent';
}
?>
        <div class="album-header">
            <h1>My Album Title</h1>
            <div class="actions">
                <button class="edit">Edit</button>
                <button class="delete">Delete</button>
                <button class="share">Share</button>
            </div>
        </div>
        
        <div class="album-details">
            <p><strong>Description:</strong> This is a description of the album.</p>
            <p><strong>Created On:</strong> January 1, 2024</p>
            <p><strong>Total Photos:</strong> 25</p>
        </div>

        <form id="search-form" action ="<?php echo $_SERVER['PHP_SELF']; ?>" method ="POST">

           <input id="search-bar" class="form-control me-sm-2" type="text"
            placeholder="Search for photos" name="search" value="<?php echo $search ?>" />

            <select
                class="form-select form-select-lg"
                name="order"
                id="order-select">
                <option <?php echo ($order === 'recent') ? 'selected': ''?> value="recent">Recent</option>
                <option <?php echo ($order === 'popular') ? 'selected':''?> value="popular">Most Popular</option>
            </select>

        </form>

        <?php
            $searchTerm = $GLOBALS['search']; 
            $order = $GLOBALS['order'];
            $searchURL = "http://localhost/PIXUP/dbModule/search.php?order=$order&user_id=1";
            //echo "<h1>$searchTerm</h1>"
            if (!empty($searchTerm)) {
                $searchURL .= "&category=$searchTerm";
            }

            $response = file_get_contents($searchURL);
          
            if ($response === false) {
                die("Error fetching the JSON data.");
            }

            $images = json_decode($response, true);
           
            if (!isset($images['success']) || !$images['success'] || empty($images['data'])) {
                echo "
                    <div class='error_message'>
                    <img src='webPictures/notFound.png' width=300px>
                    <h3> Coudn't find what you're looking for ! </h3>
                     <p> Try searching again with more specific key words </p>
                     </div>
                ";
            }else {

                echo "<div class='photo-gallery'>";
                foreach ($images['data'] as $image) {
                    $image_id = $image['image_id'];
                    $path = 'images/' . $image_id;
                    echo "
                        <div class='col-md-4 col-sm-6 mb-4'>
                            <div class='grid-item'>
                                <img src='$path' id='image.$image_id'>
                            </div>
                        </div>
                    ";
                }
                echo "</div>";

            }
          
        ?>



        
</main>
</body>
</html>