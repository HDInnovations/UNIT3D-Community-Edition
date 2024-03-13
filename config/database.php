<?php

return [

    'connections' => [
        'mysql' => [
            'driver'         => 'mysql',
            'url'            => env('DB_URL'),
            'host'           => env('DB_HOST', '127.0.0.1'),
            'port'           => env('DB_PORT', '3306'),
            'database'       => env('DB_DATABASE', 'forge'),
            'username'       => env('DB_USERNAME', 'forge'),
            'password'       => env('DB_PASSWORD', ''),
            'unix_socket'    => env('DB_SOCKET', ''),
            'charset'        => 'utf8mb4',
            'collation'      => 'utf8mb4_unicode_ci',
            'prefix'         => '',
            'prefix_indexes' => true,
            'strict'         => true,
            'engine'         => null,
            'options'        => \extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
            'dump' => [
                'dump_binary_path' => '/usr/bin', // only the path, so without `mysqldump` or `pg_dump`
                'use_single_transaction',
                'timeout'          => 60 * 10, // 10 minute timeout
                'add_extra_option' => '--password='.env('DB_PASSWORD', ''),
            ],
        ],
    ],

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => false, // disable to preserve original behavior for existing applications
    ],

    'redis' => [
        'default' => [
            'url'                => env('REDIS_URL'),
            'host'               => env('REDIS_HOST', '127.0.0.1'),
            'username'           => env('REDIS_USERNAME'),
            'password'           => env('REDIS_PASSWORD'),
            'port'               => env('REDIS_PORT', '6379'),
            'database'           => env('REDIS_DB', '0'),
            'read_write_timeout' => -1,
        ],

        'cache' => [
            'url'                => env('REDIS_URL'),
            'host'               => env('REDIS_HOST', '127.0.0.1'),
            'username'           => env('REDIS_USERNAME'),
            'password'           => env('REDIS_PASSWORD'),
            'port'               => env('REDIS_PORT', '6379'),
            'database'           => env('REDIS_CACHE_DB', '1'),
            'read_write_timeout' => -1,
        ],

        'job' => [
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
