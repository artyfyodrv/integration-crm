<?php

namespace Sync\Factories\Commands;

use Psr\Container\ContainerInterface;
use Sync\Config\BeanstalkConfig;
use Sync\Console\Commands\Producers\UpdateTokenProducer;
use Sync\Database;

class UpdateTokenProducerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new UpdateTokenProducer(new BeanstalkConfig($container), new Database(true));
    }
}
