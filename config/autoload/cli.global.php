<?php

use Sync\Console\Commands\Producers\HowTimeProducer;
use Sync\Console\Workers\HowTimeWorker;

return [
    'laminas-cli' => [
        'commands' => [
            'producer' => HowTimeProducer::class,
            'worker' => HowTimeWorker::class,
        ],
    ],
];