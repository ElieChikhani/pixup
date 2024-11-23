<?php 

$image_url = 'https://docs.imagga.com/static/images/docs/sample/japan-605234_1280.jpg';
$api_credentials = array(
    'key' => 'acc_378f87dcdde9cf5',
    'secret' => '53c2f78aa129a80363bf7096b1dc9864'
);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://api.imagga.com/v2/tags?image_url=' . urlencode($image_url));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //to be removed
curl_setopt($ch, CURLOPT_USERPWD, $api_credentials['key'] . ':' . $api_credentials['secret']);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);
} else {
    $json_response = json_decode($response);
    var_dump($json_response);
}

curl_close($ch);



?>