<?php

return [
    'prototypes' => [
        'base_path'     => env('PROTOTYPES_BASE_PATH'),
        'base_url'      => env('PROTOTYPES_BASE_URL'),
        'folder_prefix' => env('PROTOTYPES_FOLDER_PREFIX'),
    ],
    'templates' => [
        'base_path' => env('TEMPLATES_BASE_PATH'),
        'base_url'  => env('TEMPLATES_BASE_URL'),
    ],
    'client_broadcast' => [
        'channel_name' => env('CLIENT_BROADCAST_CHANNEL', 'client-channel'),
    ],
];
