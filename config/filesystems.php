<?php

return [

    'disks' => [
        'ftp' => [
            'driver'   => 'ftp',
            'host'     => 'ftp.example.com',
            'username' => 'your-username',
            'password' => 'your-password',

            // Optional FTP Settings...
            // 'port' => 21,
            // 'root' => '',
            // 'passive' => true,
            // 'ssl' => true,
            // 'timeout' => 30,
        ],

        'sftp' => [
            'driver'   => 'sftp',
            'host'     => 'example.com',
            'username' => 'your-username',
            'password' => 'your-password',

            // Settings for SSH key based authentication...
            'privateKey' => '/path/to/privateKey',
            'passphrase' => 'encryption-password',

            // Optional SFTP Settings...
            // 'port' => 22,
            // 'root' => '',
            // 'timeout' => 30,
        ],

        'backups' => [
            'driver' => 'local',
            'root'   => storage_path('backups'),
        ],

        'torrents' => [
            'driver' => 'local',
            'root'   => public_path('files/torrents'),
        ],

        'subtitles' => [
            'driver' => 'local',
            'root'   => public_path('files/subtitles'),
        ],

        'attachments' => [
            'driver' => 'local',
            'root'   => public_path('files/attachments'),
        ],
    ],

];
