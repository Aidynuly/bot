<?php

return [
    'api_key' => '1675108722:AAHiNI6dsRMwic6paMNpBFLv4J8MzfxM5Go',
    'bot_username' => 'avtoadv_bot',
    'secret' => 'supersecret',

    'webhook' => [
        'url' => 'https://telegram.avtoadvokat.kz/hook.php',
    ],

    'commands' => [
        'paths' => [
            __DIR__ . '/Commands',
        ],
        'configs' => [
        ],
    ],

    'mysql' => [
        'host' => '127.0.0.1',
        'user' => 'v-2905_telegram_bot',
        'password' => 'sQx8j2$3',
        'database' => 'v-2905_telegram_bot',
    ],

    'paths' => [
        'download' => __DIR__ . '/Download',
        'upload' => __DIR__ . '/Upload',
    ],


    'limiter' => [
        'enabled' => true,
    ]
];