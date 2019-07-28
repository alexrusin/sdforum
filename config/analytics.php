<?php

return [
    'api_token' => env('ANALYTICS_API_TOKEN'),
    'timezone' => env('ANALYTICS_TIMEZONE', 'America/Los_Angeles'),
    'database_connection' => env('ANALYTICS_DB_CONNECTION', 'mysql'),
    'path' => env('ANALYTICS_PATH', 'analytics'),
    'middleware_group' => env('ANALYTICS_MIDDLEWARE_GROUP', 'web'),
];