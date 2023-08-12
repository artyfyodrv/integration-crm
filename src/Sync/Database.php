<?php

namespace Sync;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
    public function __construct($isLocal = false)
    {
        $capsule = new Capsule();
        if ($isLocal === false) {
            $config = (include './config/autoload/database.global.php')['database']['eloquent'];
        } else {
            $config = (include './config/autoload/database.global.php')['database']['migrations'];
        }

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
    }
}