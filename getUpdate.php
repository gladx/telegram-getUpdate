<?php
$botToken = "TOKEN";

$url = "http://127.0.0.1/bot/webhook";


$apiUrl = "https://api.telegram.org/bot".$botToken;
try{

$update = file_get_contents($apiUrl."/getupdates");

if($update == false)
{
	throw new Exception("Check your internet Conncetion", 1);
}

$u = json_decode($update, true);

if($u['ok']){
    echo "Count : " . count($u['result']); 
    $update = $u['result'];
    $count = count($update);
    if ($count > 0) {
    for ($i = 0; $i < $count; $i++) {
            sendToTelegram($update[$i], $url);
        }
        $update = file_get_contents($apiUrl."/getupdates?offset=".($update[$i-1]['update_id']+1));
    } 
}

} catch(Exception $e)
{
    echo $e->getMessage() . "\n";
    echo "Check your url \n";
    exit;
}
function sendToTelegram($data, $url) {


$curl = curl_init();

    $data_string = json_encode($data);
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
    // curl_setopt($curl, CURLOPT_TIMEOUT, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT_MS, 10);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string)
    ]);

    $result = curl_exec($curl);

}
