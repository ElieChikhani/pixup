<?php


//path inhereted by include
if($path){

//CALLING THE ADAPTER THAT CALLS THE API 
$adapter_api = "http://localhost/pixup/externalAPI/imaggaAPICall.php?path=$path";
$result = file_get_contents($adapter_api); //result as a string 

$tag_result = json_decode($result, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "JSON Decode Error: " . json_last_error_msg();
    exit;
}



if(isset($tag_result['status']['type'])&&$tag_result['status']['type']==="success"){
    $tags = $tag_result['result']['tags'];
    $tag_list = array(); 

    $tags_counter = 0; 

    //getting all the tags
    forEach($tags as $tag_info){
        if($tags_counter > 10) break; 
        $tag = $tag_info['tag']['en'];
        $tag_list[] = $tag;
        $tags_counter++; 
    }
    

    //adding the tag to the image's info in database
    //connection must be alrelady open in files including this file (same for image id)
        foreach($tag_list as $tag) {
            $sql = "INSERT INTO category_image (image_id, category) VALUES ('$image_id','$tag')"; 
            if (!($conn->query($sql) === TRUE)) {
                echo "Error adding category: " . $conn->error;
                break;
            }
        }
    }else {
        echo "Connection failed";
    }
}else {
    echo "Failed to get tags";
}



?>