<?php

return [
    // Instagram Business Account ID (connected to Page)
    'business_account_id' => env('INSTAGRAM_BUSINESS_ACCOUNT_ID', env('INSTAGRAM_ID', null)),

    // Long-lived Page access token with permissions to read the IG account
    'page_access_token' => env('FACEBOOK_PAGE_ACCESS_TOKEN', null),

    // Graph API version
    'graph_version' => env('META_GRAPH_VERSION', 'v17.0'),

    // Cache TTL in seconds
    'cache_ttl' => env('INSTAGRAM_CACHE_TTL', 3600),
];
