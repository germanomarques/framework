<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | FondBot Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure channels that will be used in your application.
    | A default configuration has been added for each official drivers.
    | You are free to add as many channels as you need.
    |
    */

    'channels' => [

        'telegram' => [
            'driver' => 'telegram',
            'token' => env('TELEGRAM_TOKEN'),
        ],

        'vk' => [
            'driver' => 'vk',
            'access_token' => env('VK_ACCESS_TOKEN'),
            'confirmation_token' => env('VK_CONFIRMATION_TOKEN'),
            'secret_key' => env('VK_SECRET_KEY'),
            'group_id' => env('VK_GROUP_ID'),
        ],

    ],

];
