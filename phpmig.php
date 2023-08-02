<?php

use Illuminate\Container\Container;
use Phpmig\Adapter;
use Illuminate\Database\Capsule\Manager as Capsule;

$container = new Container();

$container['db'] = function ($c) {
    $capsule = new Capsule();
    $config = (include './config/autoload/database.global.php')['database']['migrations'];

    $capsule->addConnection([
        'driver'    => $config['driver'],
        'host'      => $config['host'],
        'port'      => $config['port'],
        'database'  => $config['database'],
        'username'  => $config['username'],
        'password'  => $config['password'],
        'charset'   => $config['charset'],
        'collation' => $config['collation'],
        'prefix'    => '',
    ]);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};

$container['phpmig.adapter'] = function($c) {
    return new Adapter\Illuminate\Database($c['db'], 'migrations');
};
$container['phpmig.migrations_path'] = __DIR__ . DIRECTORY_SEPARATOR . 'migrations';

return $container;