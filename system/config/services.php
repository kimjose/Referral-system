<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    // Add your custom services below
    'mfl' => [
        'url' => env('MFL_API_URL', 'https://api.mfl.health.go.ke'),
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
