<?php 

if(!isset($_SESSION)) session_start(); 

$image_id = isset($_GET['image_id']) ? (int)$_GET['image_id'] : null;
$action = isset($_GET['action']) ? $_GET['action'] : 'close';
$user_id = $_SESSION['user_id']; 
$username = $_SESSION['username']; 

if($action==='close'){
    unset($_SESSION['current_image_id']);
    unset($_SESSION['current_image_path']);
}else {

if(!empty($image_id)&&$action=='open'){
    //passing the id and username since such fetch request will cut the session !!
    $imageInfo = "http://localhost/PIXUP/dbModule/getImageInfo.php?image_id=$image_id&current_user=$user_id&current_username=$username"; //which is our db module that searches for the list of images.
    $result = file_get_contents($imageInfo); 
    if($result === false ){
        echo "Error occured while precessing info"; 
    }
    
    $data = json_decode($result, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $error = json_last_error_msg();
        echo "<p>An error occurred while processing the image information. Please try again later $error</p>";
    }
    
    if($data['success']){
    $imageInfoData = $data['data'];
    $image_title = isset($imageInfoData['title']) ? $imageInfoData['title'] : null;
    $image_path = isset($imageInfoData['path']) ? $imageInfoData['path'] : null;
    $image_description = isset($imageInfoData['description']) ? $imageInfoData['description'] : null;
    $image_tags = isset($imageInfoData['tags']) ? $imageInfoData['tags'] : null;
    $isOwner = isset($imageInfoData['isOwner']) ? $imageInfoData['isOwner'] : null;
    $saved = isset($imageInfoData['saved']) ? $imageInfoData['saved'] : null;
    $username = isset($imageInfoData['username']) ? $imageInfoData['username'] : null;
    $image_upload_date = isset($imageInfoData['upload_date']) ? $imageInfoData['upload_date'] : null;
    $saveCount = isset($imageInfoData['savedCount']) ? $imageInfoData['savedCount'] : null;


   //storing in the session the ucrrent image id to handle all operations on the image (save or delete)
    $_SESSION['current_image_id'] = $image_id;
    $_SESSION['current_image_path'] = $image_path;
    


    echo "
     <div class='popup-content'>
        <button class='popup-close' id='image-popup-close'> <i class='fas fa-x'> </i> </button>

       <div class='popup-body'>

     <div class='image-container'>
        <img class='popupImage' src=$image_path>
     </div>

    <div class='textual-content'>
        <h3 id='image-title'> $image_title </h3>
        <p id='upload-date'> $image_upload_date </p>
        <p id='by'> <i class = 'fas fa-user'> </i> $username  </p>
       

        <p id='description'> $image_description</p>
        <div id='saves'> <i class='fas fa-heart'> </i> Saves : <span id='saved-count'> $saveCount </span> </div>
    ";     


    echo " <div id='image-tags'>";
    forEach($image_tags as $tags){
        echo "<div class='tag'> $tags </div>";
    }
    echo "</div>"; 


    if(!empty($_SESSION['user_id'])){
      if(!$isOwner){
          if($saved){
              echo "<button type='button' class='btn btn-primary save-button unsave'> Unsave </button>";
          }else {
              echo "<button type='button' class='btn btn-primary save-button'> Save </button"; 
          }

      }else {
          echo "<button type='button' class='btn btn-primary delete-button'> Delete </button>";
      }

    }

    echo "</div>";
    echo "</div>"; 
    echo "</div>";

}else {
    echo 'image  could not be displayed'; 
}
}
}
?>







