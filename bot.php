<?php

error_reporting(E_ALL | E_WARNING);
ini_set('display_errors', 1);

require_once 'config/config.php';

function TelegramRequest(string $method, array $data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot' . BOT_TOKEN . '/' . $method);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);

    if ($response == false) {
        return 'CurlError: ' . curl_error($ch);
    }

    return json_decode($response);
}

$update = json_decode(file_get_contents('php://input'));

if (isset($update->message)) {
    $message     = $update->message ?? null;
    $text        = htmlspecialchars($message->text ?? '', ENT_QUOTES, 'UTF-8');
    $first_name  = htmlspecialchars($message->from->first_name ?? null, ENT_QUOTES, 'UTF-8');
    $user_name   = $message->from->username ?? null;
    $from_id     = $message->from->id ?? null;
    $chat_id     = $message->chat->id ?? null;
    $type        = $message->chat->type ?? null;
    $file_id     = $update->message->photo[2]->file_id ?? null;
    $message_id  = $update->message->message_id ?? null;
}

if (isset($update->callback_query)) {
    $from_id     = $update->callback_query->from->id ?? null;
    $chat_id     = $update->callback_query->message->chat->id ?? null;
    $data        = $update->callback_query->data ?? null;
    $query_id    = $update->callback_query->id ?? null;
    $type        = $update->callback_query->message->chat->type ?? null;
    $message_id  = $update->callback_query->message->message_id ?? null;
}


if ($text == '/start') {
    TelegramRequest('sendMessage', [
        'chat_id' => $from_id,
        'text'    => 'Wellcom to ce3u Bot'
    ]);

    die;
}
