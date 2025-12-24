<?php

return [

    'default' => env('MAIL_MAILER', 'smtp'),

    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'scheme' => env('MAIL_SCHEME'),
            'host' => env('MAIL_HOST', env('SMTP_HOST', '127.0.0.1')),
            'port' => env('MAIL_PORT', env('SMTP_PORT', 465)),
            'encryption' => env('MAIL_ENCRYPTION', env('SMTP_SECURE', true) ? 'ssl' : null),
            'username' => env('MAIL_USERNAME', env('SMTP_USER')),
            'password' => env('MAIL_PASSWORD', env('SMTP_PASS')),
            'timeout' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN'),
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],
    ],

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'no-reply@swbs.site'),
        'name' => env('MAIL_FROM_NAME', 'SWBS'),
    ],

    'markdown' => [
        'theme' => 'default',

        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

];