<?php

namespace Sync;

use Sync\Factories\AuthHandlerFactory;
use Sync\Factories\ContactsHandlerFactory;
use Sync\Factories\SumHandlerFactory;
use Sync\Handlers\AuthHandler;
use Sync\Handlers\ContactsHandler;
use Sync\Handlers\SumHandler;

class ConfigProvider
{
    /**
     *
     * @return array[] возвращает массив с зависимостями
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    private function getDependencies()
    {
        return [
            'invokables' => [
                'logger' => Logger::class
            ],
            'factories' => [
                SumHandler::class => SumHandlerFactory::class,
                AuthHandler::class => AuthHandlerFactory::class,
                ContactsHandler::class => ContactsHandlerFactory::class,
            ],
        ];
    }
}