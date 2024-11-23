<?php
$searchURL = "http://localhost/PIXUP/dbModule/searchImage.php?order=$order"; //which is our db module that searches for the list of images. 
if (!empty($search)) $searchURL .= "&category=$search";
if (!empty($album_id)) $searchURL .= "&album_id=$album_id";
if (!empty($user_id)) $searchURL .= "&user_id=$user_id";
if (!empty($saved)) $searchURL .= "&saved=$saved";

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
    foreach ($images['data'] as $image_id => $image_path) {
        echo "<div class='grid-item'><img src='$image_path' id='image$image_id'></div>";
    }
    echo "</div>";

}

include 'imageInfo.php'; 

?>