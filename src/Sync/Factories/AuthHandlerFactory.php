<?php

namespace Sync\Factories;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Handlers\AuthHandler;

/**
 * Фабрика для создания экземпляра AuthHandler
 */
class AuthHandlerFactory
{
    /**
     * @param ContainerInterface $container контейнер зависимостей
     * @return RequestHandlerInterface возвращает экземпляр AuthHandler
     */
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new AuthHandler();
    }
}