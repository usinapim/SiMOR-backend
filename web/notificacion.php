<?php
$to="eKLdetexbqU:APA91bEwE0mFNR2zIqqdaiTTbMi6UXhdaY2Pzeheasf809DslxHfjVVjKAzxh-LwOPcb6SdIpRrVyRVLJMmURg3iPq5gdlQfhBWtbKpqSqVq2pw67nBVxrBzXwDqCxThwBVGobVBci06";
$title="Push Notification Title";
$message="Push Notification Message";
sendPush($to,$title,$message);

function sendPush($to,$title,$message)
{
// API access key from Google API's Console
// replace API
define( 'API_ACCESS_KEY', 'AIzaSyDsz2MQCM4yw9M-7BOVx1UhvWIVDJ_VgLc');
$registrationIds = array($to);
$msg = array
(
'message' => $message,
'title' => $title,
'vibrate' => 1,
'sound' => 1

// you can also add images, additionalData
);
$fields = array
(
'registration_ids' => $registrationIds,
'data' => $msg
);
$headers = array
(
'Authorization: key=' . API_ACCESS_KEY,
'Content-Type: application/json'
);
$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );
echo $result;
}
?>

