<?php

namespace Sync\Factories\Commands;

use Psr\Container\ContainerInterface;
use Sync\Command\HowTimeCommand;

class HowTimeCommandFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new HowTimeCommand();
    }
}