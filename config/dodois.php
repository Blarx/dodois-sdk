<?php

return [
    /*
    |--------------------------------------------------------------------------
    | DODO IS Client Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure a connection to connect to the DodoIs API
    | and specify additional configuration options.
    |
    */

    'connection' => [
        'clientId' => env('DODOIS_CLIENTID', ''),
        'clientSecret' => env('DODOIS_SECRET', ''),
        'callbackUri' => env('DODOIS_REDIRECTURI', '/dodois/callback'),
        'redirectUri' => '/dashboard',
    ],

    'scope' => implode(' ', [
        'openid offline_access', // User information && refresh_token
        'user.role:read', // auth/roles/list && auth/roles/units
        'productionefficiency', // production/orders-handover-time && production/productivity
        'stopsales', // production/stop-sales-channels && production/stop-sales-ingredients && production/stop-sales-products
        'deliverystatistics', // delivery/statistics/ && delivery/vouchers
        'organizationstructure', // organization-structure/legal-entities
        'products', // accounting/products
        'stockitems', // accounting/stock-items && accounting/local-stock-items
        'production', // accounting/semi-finished-products-production
        'sales', // accounting/sales
        'incentives', // staff/incentives-by-members
    ]),
];
