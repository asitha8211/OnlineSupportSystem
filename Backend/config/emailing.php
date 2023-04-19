<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache connection that gets used while
    | using this caching library. This connection is used when another is
    | not explicitly specified when executing a given caching function.
    |
    | Supported: "apc", "array", "database", "file",
    |            "memcached", "redis", "dynamodb"
    |
    */

    'sender_email' => env('MAIL_FROM_ADDRESS'),
    'sender_name' => env('MAIL_FROM_NAME'),
    'sender_password' => env('MAIL_PASSWORD'),
    'sender_host' => env('MAIL_HOST'),
    'sender_user_name' => env('MAIL_USERNAME'),
    'sender_port' => env('MAIL_PORT')

];
