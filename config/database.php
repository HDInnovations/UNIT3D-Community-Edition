<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver'                  => 'sqlite',
            'url'                     => env('DATABASE_URL'),
            'database'                => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix'                  => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => [
            'driver'         => 'mysql',
            'url'            => env('DATABASE_URL'),
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
            'options'        => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],

            'dump' => [
                'dump_binary_path' => '/usr/bin', // only the path, so without `mysqldump` or `pg_dump`
                'use_single_transaction',
                'timeout'          => 60 * 10, // 10 minute timeout
                'add_extra_option' => '--password='.env('DB_PASSWORD', ''),
            ],
        ],

        'pgsql' => [
            'driver'              => 'pgsql',
            'url'                 => env('DATABASE_URL'),
            'host'                => env('DB_HOST', '127.0.0.1'),
            'port'                => env('DB_PORT', '5432'),
            'database'            => env('DB_DATABASE', 'forge'),
            'username'            => env('DB_USERNAME', 'forge'),
            'password'            => env('DB_PASSWORD', ''),
            'charset'             => 'utf8',
            'prefix'              => '',
            'prefix_indexes'      => true,
            'search_path'         => 'public',
            'sslmode'             => 'prefer',
        ],

        'sqlsrv' => [
            'driver'         => 'sqlsrv',
            'url'            => env('DATABASE_URL'),
            'host'           => env('DB_HOST', 'localhost'),
            'port'           => env('DB_PORT', '1433'),
            'database'       => env('DB_DATABASE', 'forge'),
            'username'       => env('DB_USERNAME', 'forge'),
            'password'       => env('DB_PASSWORD', ''),
            'charset'        => 'utf8',
            'prefix'         => '',
            'prefix_indexes' => true,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'predis'),

        'default' => [
            'url'                => env('REDIS_URL'),
            'host'               => env('REDIS_HOST', '127.0.0.1'),
            'username'           => env('REDIS_USERNAME'),
            'password'           => env('REDIS_PASSWORD', null),
            'port'               => env('REDIS_PORT', 6379),
            'database'           => env('REDIS_DB', 0),
            'read_write_timeout' => -1,
        ],

        'cache' => [
            'url'                => env('REDIS_URL'),
            'host'               => env('REDIS_HOST', '127.0.0.1'),
            'username'           => env('REDIS_USERNAME'),
            'password'           => env('REDIS_PASSWORD', null),
            'port'               => env('REDIS_PORT', 6379),
            'database'           => env('REDIS_CACHE_DB', 1),
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

    ],

    'pristine-db-file' => env('PRISTINE_DB_FILE'),

];
