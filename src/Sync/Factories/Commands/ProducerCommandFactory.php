<?php

namespace Sync\Factories\Commands;

use Psr\Container\ContainerInterface;
use Sync\Command\ProducerCommand;

class ProducerCommandFactory
{
    /**
     * @param ContainerInterface $container
     * @return ProducerCommand
     */
    public function __invoke(ContainerInterface $container)
    {
        return new ProducerCommand();
    }
}