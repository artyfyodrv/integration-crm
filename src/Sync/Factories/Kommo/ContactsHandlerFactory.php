<?php

namespace Sync\Factories\Kommo;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Handlers\Kommo\ContactsHandler;

/**
 * Фабрика для создания экземпляра ContactHandler
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