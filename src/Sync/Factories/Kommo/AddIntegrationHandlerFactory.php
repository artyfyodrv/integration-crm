<?php

namespace Sync\Factories\Kommo;

use Psr\Container\ContainerInterface;
use Sync\Handlers\Kommo\AddIntegrationHandler;

class AddIntegrationHandlerFactory
{
    /**
     * @param ContainerInterface $container контейнер зависимостей
     * @return AddIntegrationHandler - возвращает экземпляр AddIntegrationHandler
     */
    public function __invoke(ContainerInterface $container)
    {
        return new AddIntegrationHandler();
    }
}