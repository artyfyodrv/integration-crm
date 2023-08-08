<?php

namespace Sync\Factories\Commands;

use Psr\Container\ContainerInterface;
use Sync\Config\BeanstalkConfig;
use Sync\Console\Commands\Producers\HowTimeProducer;

class HowTimeProducerFactory
{
    /**
     * @param ContainerInterface $container - контейнер зависимостей
     * @return HowTimeProducer - возвращаем объект
     */
    public function __invoke(ContainerInterface $container)
    {
        return new HowTimeProducer(new BeanstalkConfig($container));
    }
}
