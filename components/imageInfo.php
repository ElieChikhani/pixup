<?php 

if(!isset($_SESSION)) session_start(); 

$image_id = isset($_GET['image_id']) ? $_GET['image_id'] : null;
$action = isset($_GET['action']) ? $_GET['action'] : 'close';
$seletion = isset($_GET['selection']) ? (bool) $_GET['selection'] : false;


if($action==='close'){
    unset($_SESSION['current_image_id']);
    unset($_SESSION['current_image_path']);
}else {



if(!empty($image_id)&&$action=='open'){
    //passing the id and username since such fetch request will cut the session !!
    $imageInfo = "http://localhost/PIXUP/dbModule/getImageInfo.php?image_id=$image_id"; //which is our db module that searches for the list of images.
    if(!empty($_SESSION['user_id'])&&!empty($_SESSION['username'])) {
    $imageInfo .= "&current_user=".$_SESSION['user_id']."&current_username=".$_SESSION['username'];
    }


    $result = file_get_contents($imageInfo); 
    if($result === false ){
        die ("Error occured while precessing info"); 
    }
    
    $data = json_decode($result, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $error = json_last_error_msg();
        die ("Error occured in json");
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
    $owner_id = isset($imageInfoData['owner_id']) ? $imageInfoData['owner_id'] : null;




   //storing in the session the ucrrent image id to handle all operations on the image (save or delete)
    $_SESSION['current_image_id'] = $image_id;
    $_SESSION['current_image_path'] = $image_path;

    if(!empty($image_upload_date)) $image_upload_date= date("F j, Y", strtotime($image_upload_date)); 
    


    echo "
     <div class='popup-content'>
        <button class='popup-close' id='image-popup-close'> <i class='fas fa-x'> </i> </button>

       <div class='popup-body'>

     <div class='image-container'>
        <img class='popupImage' src=$image_path>
     </div>

    <div class='textual-content'>
        <h3 id='image-title'> $image_title </h3>
        <p id='upload-date'> Uploaded on $image_upload_date </p>
        <a id='by' href='userprofile.php?user_id=$owner_id'> <i class = 'fas fa-user'> </i> $username  </a>
       

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

    if(!empty($selection) && $selection){
        echo "<button type='button' class='btn btn-primary remove-button'> Remove from album </button>";

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







