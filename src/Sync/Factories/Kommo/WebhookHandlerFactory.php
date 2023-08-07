<?php

namespace Sync\Factories\Kommo;

use Psr\Container\ContainerInterface;
use Sync\Handlers\Kommo\WebhookHandler;

/**
 * Фабрика для создания экземпляра WebhookHandler
 */
class WebhookHandlerFactory
{
    /**
     * @param ContainerInterface $container контейнер зависимостей
     * @return WebhookHandler возвращает экземпляр WebhookHandler
     */
    public function __invoke(ContainerInterface $container)
    {
        return new WebhookHandler();
    }
}