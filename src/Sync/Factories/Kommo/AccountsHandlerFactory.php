<?php

namespace Sync\Factories\Kommo;

use Psr\Container\ContainerInterface;
use Sync\Handlers\AccountsHandler;

class AccountsHandlerFactory
{
    /**
     * @param ContainerInterface $container контейнер зависимостей
     * @return AccountsHandler - возвращает экземпляр AddIntegrationHandler
     */
    public function __invoke(ContainerInterface $container)
    {
        return new AccountsHandler();
    }
}