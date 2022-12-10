<?php

  /**
   * developer: @DevSinam ( on Telegram )
   * channel: @AlphaCreate
   */

define('API_KEY', 'token'); //Token

if (!is_file('webhook.lock')){
    $set = bot('setwebhook', ['url' => 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 'allowed_updates' => ['message']]);
    if ($set->ok == true){
        echo '<h1 style="text-align: center;margin-top:30px">Webhook was set</h1>';
        @file_put_contents('webhook.lock', '');
    }else{
        echo '<h1 style="text-align: center;margin-top:30px">invalid token or ssl issues</h1>';
}

function bot($method,$datas=[]){
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://api.telegram.org/bot' . API_KEY . '/' . $method,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => $datas
    ]);
    return json_decode(curl_exec($ch));
}

$update = json_decode(file_get_contents('php://input'));
$entities = $update->message->entities;
$en_type = $entities->type;
$en_lenght = $entities->length;
$chat = $update->message->chat;
$type = $chat->type;
$fromid = $update->message->from->id;
$chatid = $update->message->chat->id;
$msgid = $update->message->message_id;
$get = file_get_contents("https://api.telegram.org/botAPI_KEY/getChatMember?chat_id=$chatid&user_id=".$fromid);
$info = json_decode($get, true);
$rank = $info['result']['status'];

if($type !== 'private'){
if($en_type == 'url' or $en_type == 'text_link'){
if($rank !== 'creator' or $rank !== 'administrator'){
bot('deletemessage',['chat_id'=>$chatid,'message_id'=>$msgid]);
}}}
?>