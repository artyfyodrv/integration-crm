<?php

use Sync\Console\Commands\Producers\HowTimeProducer;
use Sync\Console\Commands\Producers\UpdateTokenProducer;
use Sync\Console\Workers\HowTimeWorker;
use Sync\Console\Workers\UpdateTokenWorker;

return [
    'laminas-cli' => [
        'commands' => [
            'producer' => HowTimeProducer::class,
            'worker' => HowTimeWorker::class,
            'update-token' => UpdateTokenProducer::class,
            'update-token-worker' => UpdateTokenWorker::class,
        ],
    ],
];