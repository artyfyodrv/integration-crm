<?php

namespace Sync\Factories\Commands;

use Interop\Container\ContainerInterface;
use Sync\Config\BeanstalkConfig;
use Sync\Console\Workers\HowTimeWorker;

class HowTimeWorkerFactory
{
    /**
     * @param ContainerInterface $container - Контейнер зависимостей
     * @return HowTimeWorker - Объект класса HowTimeWorker
     */
    public function __invoke(ContainerInterface $container)
    {
        return new HowTimeWorker(new BeanstalkConfig($container));
    }
}
