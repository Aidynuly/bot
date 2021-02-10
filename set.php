<?php

require_once __DIR__ . '/vendor/autoload.php';

$config = require __DIR__ . '/config.php';

try{
    $telegram = new Longman\TelegramBot\Telegram($config['api_key'], $config['bot_username']);

    $result = $telegram->setWebhook($config['webhook']['url']);
    if ($result->isOk()){
        echo $result->getDescription();
    }

} catch (Longman\TelegramBot\Exception\TelegramException $e){
    echo $e->getMessage();
}
