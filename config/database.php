<?php

return [
    'connections' => [
        'mysql' => [
            'collation' => 'utf8mb4_unicode_ci',
            'dump'      => [
                'dump_binary_path' => '/usr/bin', // only the path, so without `mysqldump` or `pg_dump`
                'use_single_transaction',
                'timeout'          => 60 * 10, // 10 minute timeout
                'add_extra_option' => '--password='.env('DB_PASSWORD', ''),
            ],
        ],
    ],

    'redis' => [
        'default' => [
            'read_write_timeout' => -1,
        ],

        'read_write_timeout' => -1,
        'job'                => [
            'url'                => env('REDIS_URL'),
            'host'               => env('REDIS_HOST', '127.0.0.1'),
            'username'           => env('REDIS_USERNAME'),
            'password'           => env('REDIS_PASSWORD', null),
            'port'               => env('REDIS_PORT', 6379),
            'database'           => env('REDIS_JOB_DB', 2),
            'read_write_timeout' => -1,
        ],

        'broadcast' => [
            'url'                => env('REDIS_URL'),
            'host'               => env('REDIS_HOST', '127.0.0.1'),
            'username'           => env('REDIS_USERNAME'),
            'password'           => env('REDIS_PASSWORD', null),
            'port'               => env('REDIS_PORT', 6379),
            'database'           => env('REDIS_BROADCAST_DB', 3),
            'read_write_timeout' => -1,
        ],

        'session' => [
            'url'                => env('REDIS_URL'),
            'host'               => env('REDIS_HOST', '127.0.0.1'),
            'username'           => env('REDIS_USERNAME'),
            'password'           => env('REDIS_PASSWORD', null),
            'port'               => env('REDIS_PORT', 6379),
            'database'           => env('REDIS_BROADCAST_DB', 4),
            'read_write_timeout' => -1,
        ],

        'announce' => [
            'url'                => env('REDIS_URL'),
            'host'               => env('REDIS_HOST', '127.0.0.1'),
            'username'           => env('REDIS_USERNAME'),
            'password'           => env('REDIS_PASSWORD', null),
            'port'               => env('REDIS_PORT', 6379),
            'database'           => env('REDIS_BROADCAST_DB', 5),
            'read_write_timeout' => -1,
        ],
    ],

    'pristine-db-file' => env('PRISTINE_DB_FILE'),
];
