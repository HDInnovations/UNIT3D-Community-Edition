<?php

return [
    'guards' => [
        'api' => [
            'driver'   => 'token',
            'provider' => 'users',
            'hash'     => false,
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'cache-user',
            'model'  => App\Models\User::class,
        ],
    ],

    'verification' => [
        'expire' => 1440,
    ],
];
