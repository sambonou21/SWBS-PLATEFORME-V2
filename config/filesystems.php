<?php

return [

    'default' => env('FILESYSTEM_DISK', 'public'),

    'disks' => [

        'public' => [
            'driver' => 'local',
            'root' => public_path('uploads'),
            'url' => env('APP_URL').'/uploads',
            'visibility' => 'public',
            'throw' => false,
        ],

        'services' => [
            'driver' => 'local',
            'root' => public_path('uploads/services'),
            'url' => env('APP_URL').'/uploads/services',
            'visibility' => 'public',
            'throw' => false,
        ],

        'portfolio' => [
            'driver' => 'local',
            'root' => public_path('uploads/portfolio'),
            'url' => env('APP_URL').'/uploads/portfolio',
            'visibility' => 'public',
            'throw' => false,
        ],

        'products' => [
            'driver' => 'local',
            'root' => public_path('uploads/products'),
            'url' => env('APP_URL').'/uploads/products',
            'visibility' => 'public',
            'throw' => false,
        ],

    ],

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];