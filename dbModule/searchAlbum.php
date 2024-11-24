<?php



$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;

if(!empty($user_id)){
    include 'connectToDB.php';

    $sql = "SELECT album_id, album_name, path FROM (albums a NATURAL JOIN album_image ai) JOIN images i on ai.image_id = i.image_id 
    WHERE a.user_id = $user_id AND ai.album_image_date = ( select MAX(ai2.album_image_date) FROM album_image ai2 WHERE ai2.album_id = ai.album_id)";
    


    $result = $conn->query($sql);
    $albums = array(); 

    if($result){

        while ($row = $result->fetch_assoc()) {
            $albums[] = [
                'album_id' => $row['album_id'], 
                'album_name' => $row['album_name'], 
                'recent_image_path' => $row['path'] 
            ];
            
        }

        
        $response = [
            'status' => 'success',
            'albums' => $albums
        ]; 

    }else {

        $response = [
            'status' => 'failed',
            'message' => 'coudnt fetch from db'
        ]; 

    }


}else {
    $response = [
        'status' => 'failed',
        'message' => 'no user id'
    ]; 
}

$conn->close();

// Send JSON response
header("Content-Type: application/json");
echo json_encode($response);



?>