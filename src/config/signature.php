<?php
return [

    'default' => 'app',

    'app'   => [
        'base_url'  => env('APP_URL'),
        'key'       => env('APP_KEY'),
        'nonce_length'  => 6,
        'country'   => env('APP_COUNTRY', 'MY'),
        'secret'    => 'NP'
    ],
];
