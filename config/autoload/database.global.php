<?php


declare(strict_types=1);

$databaseConnection = [
        'driver' => 'mysql',
        'database' => 'app_db',
        'username' => 'admin',
        'password' => '12345',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
];

return [
    'database' => [
        'migrations' => array_merge($databaseConnection, [
            'host' => '127.0.0.1',
            'port' => 3306
        ]),
        'eloquent' => array_merge($databaseConnection, [
            'host' => 'application-mysql',
            'port' => 3306
        ]),
    ],
];