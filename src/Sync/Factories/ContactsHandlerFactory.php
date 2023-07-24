<?php

namespace Sync\Factories;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Handlers\ContactsHandler;

/**
 * Фабрика для создания экземпляра ContactsHandler
 */
class ContactsHandlerFactory
{
    /**
     * @param ContainerInterface $container контейнер зависимостей
     * @return RequestHandlerInterface возвращает экземпляр класса SumHandler
     */
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new ContactsHandler();
    }

}