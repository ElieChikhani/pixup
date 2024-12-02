<?php 

//checking if the user owns the album and it's not the All album


function canEditAlbum($album_id,$user_id) {

$imageInfo = "http://localhost/PIXUP/dbModule/getAlbumInfo.php?album_id=$album_id"; //which is our db module that searches for the list of images.

$result = file_get_contents($imageInfo); 
if($result === false ){
   return false;
}

$data = json_decode($result, true);
if (json_last_error() !== JSON_ERROR_NONE) {
   return false; 
}

if(!$data['success']) return false; 

$album_info = $data['album_info']; 

if($album_info['owner_id'] != $user_id || $album_info['album_name'] == 'All' ) return false; 

return true;

}
    




?>