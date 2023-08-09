<?php

namespace Sync\Factories\Commands;

use Psr\Container\ContainerInterface;
use Sync\Config\BeanstalkConfig;
use Sync\Console\Workers\UpdateTokenWorker;
use Sync\Database;

class UpdateTokenWorkerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new UpdateTokenWorker(new BeanstalkConfig($container));
    }
}
