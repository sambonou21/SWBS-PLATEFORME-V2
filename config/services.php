<?php

return [

    'fedepay' => [
        'public' => env('FEDEPAY_PUBLIC'),
        'secret' => env('FEDEPAY_SECRET'),
        'mode' => env('FEDEPAY_MODE', 'sandbox'),
    ],

    'ai' => [
        'provider' => env('AI_PROVIDER'),
        'api_key' => env('AI_API_KEY'),
        'model' => env('AI_MODEL', 'gpt-4o-mini'),
        'instructions' => env('AI_INSTRUCTIONS', ''),
    ],

];