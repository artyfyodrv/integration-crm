<?php

namespace Sync\Factories\Kommo;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Handlers\Kommo\SendHandler;

/**
 * Фабрика для создания экземпляра SendHandler
 */
class SendHandlerFactory
{
    /**
     * @param ContainerInterface $container контейнер зависимостей
     * @return RequestHandlerInterface возвращает экземпляр AuthHandler
     */
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new SendHandler();
    }
}