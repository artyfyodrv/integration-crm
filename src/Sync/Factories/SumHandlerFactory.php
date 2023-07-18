<?php

namespace Sync\Factories;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Handlers\SumHandler;

/**
 * Фабрика для создания экземпляра SumHandler
 */
class SumHandlerFactory
{
    /**
     * @param ContainerInterface $container контейнер зависимостей
     * @return RequestHandlerInterface возвращает экземпляр класса SumHandler
     */
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new SumHandler();
    }
}