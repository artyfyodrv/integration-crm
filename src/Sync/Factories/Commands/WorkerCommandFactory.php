<?php

namespace Sync\Factories\Commands;

use Interop\Container\ContainerInterface;
use Sync\Command\WorkerCommand;

class WorkerCommandFactory
{
    /**
     * @param ContainerInterface $container
     * @return WorkerCommand
     */
    public function __invoke(ContainerInterface $container)
    {
        return new WorkerCommand();
    }
}