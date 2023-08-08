<?php

namespace Sync\Console\Workers;

class HowTimeWorker extends BaseWorker
{
    /** @var string название очереди */
    protected string $queue = 'how-time';

    /**
     *  Обработчик задания из очереди
     */
    public function process($data)
    {
        echo "Текущее время: $data" . PHP_EOL;
    }
}
