<?php

return [
    // ... existing services ...

    'mfl' => [
        'base_url' => env('MFL_API_URL', 'https://api.mfl.health.go.ke/api/v1'),
        'api_key' => env('MFL_API_KEY'),
    ],

    'echis' => [
        'base_url' => env('ECHIS_API_URL'),
        'api_key' => env('ECHIS_API_KEY'),
    ],

    'shr' => [
        'base_url' => env('SHR_API_URL'),
        'api_key' => env('SHR_API_KEY'),
    ],

    'hie' => [
        'base_url' => env('HIE_API_URL'),
        'api_key' => env('HIE_API_KEY'),
    ],
]; 