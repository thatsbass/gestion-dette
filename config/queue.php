<?php

return [

'default' => env('QUEUE_CONNECTION', 'sync'),

'connections' => [

    'sync' => [
        'driver' => 'sync',
    ],

    'database' => [
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
    ],

    // Autres configurations

],

'failed' => [
    'driver' => 'database',
    'table' => 'failed_jobs',
    'database' => env('DB_CONNECTION', 'mysql'),
    'connection' => env('DB_CONNECTION', 'mysql'),
],

];
