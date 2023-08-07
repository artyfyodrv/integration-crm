<?php

return [
    'laminas-cli' => [
        'commands' => [
            'how-time' => \Sync\Command\HowTimeCommand::class,
            'producer' => \Sync\Command\ProducerCommand::class,
            'worker' => \Sync\Command\WorkerCommand::class,
        ],
    ],
];