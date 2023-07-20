<?php

namespace Sync\Factories;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Handlers\AuthHandler;

class AuthHandlerFactory
{
    /**
     * @param ContainerInterface $request контейнер зависимостей
     * @return RequestHandlerInterface возвращает экземпляр AuthHandler
     */
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new AuthHandler();
    }
}