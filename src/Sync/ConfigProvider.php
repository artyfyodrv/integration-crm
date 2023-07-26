<?php

namespace Sync;

use Sync\Factories\Kommo\AuthHandlerFactory;
use Sync\Factories\Kommo\ContactsHandlerFactory;
use Sync\Factories\SumHandlerFactory;
use Sync\Factories\Unisender\ContactHandlerFactory;
use Sync\Handlers\Kommo\AuthHandler;
use Sync\Handlers\Kommo\ContactsHandler;
use Sync\Handlers\SumHandler;
use Sync\Handlers\Unisender\ContactHandler;


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
            ],
            'factories' => [
                SumHandler::class => SumHandlerFactory::class,
                AuthHandler::class => AuthHandlerFactory::class,
                ContactsHandler::class => ContactsHandlerFactory::class,
                ContactHandler::class => ContactHandlerFactory::class
            ],
        ];
    }
}