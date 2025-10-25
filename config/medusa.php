<?php

return [
    'base_url' => env('MEDUSA_BASE_URL', 'http://localhost:9000'),
    'api_key' => env('MEDUSA_API_KEY', ''),
    'timeout' => env('MEDUSA_TIMEOUT', 30),
    'cache_ttl' => env('MEDUSA_CACHE_TTL', 300),
    
    'sync' => [
        'enabled' => env('MEDUSA_SYNC_ENABLED', true),
        'auto_sync' => env('MEDUSA_AUTO_SYNC', false),
        'sync_on_create' => env('MEDUSA_SYNC_ON_CREATE', true),
        'sync_on_update' => env('MEDUSA_SYNC_ON_UPDATE', true),
        'sync_on_delete' => env('MEDUSA_SYNC_ON_DELETE', true),
    ],
    
    'mapping' => [
        'status' => [
            'Active' => 'published',
            'Draft' => 'draft',
            'Supplier Product' => 'draft',
        ],
        'default_weight' => 0.5,
        'default_currency' => 'USD',
        'default_origin_country' => 'US',
    ]
];





