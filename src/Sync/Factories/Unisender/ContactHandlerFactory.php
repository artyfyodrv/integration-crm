<?php

namespace Sync\Factories\Unisender;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Handlers\Unisender\ContactHandler;

/**
 * Фабрика для создания экземпляра ContactHandler
 */
class ContactHandlerFactory
{
    /**
     * @param ContainerInterface $container контейнер зависимостей
     * @return RequestHandlerInterface возвращает экземпляр класса SumHandler
     */
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new ContactHandler();
    }
}