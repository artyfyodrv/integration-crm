<?php

namespace Sync\Factories\Kommo;

use Psr\Container\ContainerInterface;
use Sync\Handlers\Kommo\WidgetHandler;

/**
 * Фабрика для создания экземпляра WidgetHandler
 */
class WidgetHandlerFactory
{
    /**
     * @param ContainerInterface $container контейнер зависимостей
     * @return WidgetHandler - возвращает экземпляр WidgetHandler
     */
    public function __invoke(ContainerInterface $container)
    {
        return new WidgetHandler();
    }

}