<?php


//path inhereted by include
if($path){

//CALLING THE ADAPTER THAT CALLS THE API 
$adapter_api = "http://localhost/pixup/externalAPI/imaggaAPICall.php?path=$path";
$result = file_get_contents($adapter_api); //result as a string 

$tag_result = json_decode($result, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "JSON Decode Error: " . json_last_error_msg();
    echo "Raw response: " . $result;
    exit;
}



if(isset($tag_result['status']['type'])&&$tag_result['status']['type']==="success"){
    $tags = $tag_result['result']['tags'];
    $tag_list = array(); 

    //getting all the tags
    forEach($tags as $tag_info){
        $confidence = $tag_info['confidence']; 
        $tag = $tag_info['tag']['en'];

        if($confidence > 40){
            $tag_list[] = $tag;
        }else {
            break; 
        }
    }

    //adding the tag to the image's info in database

    //connection must be a;relady ope in files including this file (same for image id)
    if($conn){
        foreach($tag_list as $tag) {
            $sql = "INSERT INTO category_image (image_id, category) VALUES ('$image_id','$tag')"; 
            if (!($conn->query($sql) === TRUE)) {
                echo "Error incrementing imageCount: " . $conn->error;
                break;
            }
        }
    }else {
        echo "Connection failed";
    }
}else {
    echo "Failed to get tags";
}
}


?>