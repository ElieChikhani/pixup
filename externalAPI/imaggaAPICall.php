<?php

$api_key = 'acc_378f87dcdde9cf5';
$api_secret = '53c2f78aa129a80363bf7096b1dc9864';

$path = isset($_GET['path']) ? $_GET['path'] : null;

//image id is shared within the namespace of uploadImage via include
if(isset($path) && !empty($path)){
$image_path = '../'.$path; 

// Initializing cURL for image upload
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.imagga.com/v2/uploads');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //to be removed
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'image' => curl_file_create($image_path)
]);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Basic ' . base64_encode($api_key . ':' . $api_secret)
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//upload request (because we need the upload id)
$response = curl_exec($ch);

//error handleing
if (curl_errno($ch)) {
    echo 'Upload Error: ' . curl_error($ch);
    curl_close($ch);
    exit;
}

// Getting the upload response and upload_id
$result = json_decode($response, true);

if (isset($result['result']['upload_id'])) {
    $upload_id = $result['result']['upload_id'];
} else {
    echo "Upload failed: " . $response;
    curl_close($ch);
    exit;
}

// Close cURL after upload
curl_close($ch);


$ch = curl_init();
$tagging_url = 'https://api.imagga.com/v2/tags?image_upload_id=' . $upload_id;
curl_setopt($ch, CURLOPT_URL, $tagging_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //to be removed
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Basic ' . base64_encode($api_key . ':' . $api_secret) //the image must be in base64 
]);

// Execute the tagging request
$response = curl_exec($ch);

// Check for errors during tagging
if (curl_errno($ch)) {
    echo json_encode([
        'status' => ['type' => 'error', 'text' => 'Tagging error']
    ]);
} else {
    echo $response;
}

// Close cURL after tagging
curl_close($ch);
} else {

   echo json_encode([
        'status' => ['type' => 'error', 'text' => 'No valid path provided']
    ]);
}
?>
