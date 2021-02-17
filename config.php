<?php
require_once "vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// curl -X POST -F 'url=https://f6c42add08fb.ngrok.io/hook.php' https://api.telegram.org/bot1675108722:AAHiNI6dsRMwic6paMNpBFLv4J8MzfxM5Go/setWebhook

return [
    'api_key' => $_ENV['BOT_API_KEY'],
    'bot_username' => $_ENV['BOT_USERNAME'],
    'secret' => 'supersecret',

    'webhook' => [
        'url' => $_ENV['WEBHOOK'],
    ],

    'commands' => [
        'paths' => [
            __DIR__ . '/Commands',
        ],
        'configs' => [

        ],
    ],

    'mysql' => [
        'host' => $_ENV['DB_HOST'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
        'database' => $_ENV['DB_NAME']
    ],

    'paths' => [
        'download' => __DIR__ . '/Download',
        'upload' => __DIR__ . '/Upload',
    ],


    'limiter' => [
        'enabled' => true,
    ]
];
