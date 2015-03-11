<?php
$client_id = 'YOUR_APP_ID'; // Instagram app id
$client_secret = 'YOUR_APP_SECRET'; // Instagram app secret
$redirect_uri = 'YOUR_CALLBACK_URI'; // Instagram app callback url
$apiData = array(
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'redirect_uri' => $redirect_uri,
    'aspect' => "media",
    'object' => "tag",
    'object_id' => "YOUR_HASHTAG", // Hashtag - Change it here and in "callback.php" on line 17, after that request this file to register a new subscription
    'callback_url' => $redirect_uri
);

$apiHost = 'https://api.instagram.com/v1/subscriptions/';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiHost);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($apiData));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$jsonData = curl_exec($ch);
curl_close($ch);
var_dump($jsonData); // Subscription summary or error
