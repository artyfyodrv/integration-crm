<?php

namespace Sync;

use Sync\Factories\Kommo\AccountsHandlerFactory;
use Sync\Factories\Kommo\AddIntegrationHandlerFactory;
use Sync\Factories\Kommo\AuthHandlerFactory;
use Sync\Factories\Kommo\ContactsHandlerFactory;
use Sync\Factories\Kommo\SendHandlerFactory;
use Sync\Factories\Kommo\WebhookHandlerFactory;
use Sync\Factories\Kommo\WidgetHandlerFactory;
use Sync\Factories\SumHandlerFactory;
use Sync\Factories\Unisender\ContactHandlerFactory;
use Sync\Handlers\AccountsHandler;
use Sync\Handlers\Kommo\AddIntegrationHandler;
use Sync\Handlers\Kommo\AuthHandler;
use Sync\Handlers\Kommo\ContactsHandler;
use Sync\Handlers\Kommo\SendHandler;
use Sync\Handlers\Kommo\WebhookHandler;
use Sync\Handlers\Kommo\WidgetHandler;
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
                ContactHandler::class => ContactHandlerFactory::class,
                SendHandler::class => SendHandlerFactory::class,
                AddIntegrationHandler::class => AddIntegrationHandlerFactory::class,
                WidgetHandler::class => WidgetHandlerFactory::class,
                AccountsHandler::class => AccountsHandlerFactory::class,
                WebhookHandler::class => WebhookHandlerFactory::class
            ],
        ];
    }
}