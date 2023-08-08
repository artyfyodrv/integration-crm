<?php

namespace Sync\Console\Workers;

use Pheanstalk\Pheanstalk;


class HowTimeWorker extends BaseWorker
{
    protected Pheanstalk $connection;

    protected string $queue = 'how-time';

    public function process($data)
    {
        echo "Текущее время: $data" . PHP_EOL;
    }
}