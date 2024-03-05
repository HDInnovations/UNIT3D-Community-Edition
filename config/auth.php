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
        ],
    ],

    'verification' => [
        'expire' => 1440,
    ],
];
