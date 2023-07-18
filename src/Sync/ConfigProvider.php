<?php

namespace Sync;

use Sync\Factories\SumHandlerFactory;
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

            ],
            'factories' => [
                SumHandler::class => SumHandlerFactory::class,
            ],
        ];
    }
}