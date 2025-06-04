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
        'outbound' => [
            'enabled' => env('ECHIS_OUTBOUND_ENABLED', true),
            'webhook_url' => env('ECHIS_WEBHOOK_URL'),
            'auth' => [
                'type' => 'header',
                'name' => 'Authorization',
                'value_key' => 'echis_webhook'
            ],
            'mapping' => [
                'patient_id' => 'doc.fields.patient_id',
                'facility_id' => 'doc.fields.facility_id',
                'referral_id' => 'doc.fields.referral_id',
                'status' => 'doc.fields.status',
                'notes' => 'doc.fields.notes',
                'created_at' => 'doc.created_at'
            ]
        ]
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