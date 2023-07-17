<?php

namespace Sync\Factories;

use Psr\Container\ContainerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * Фабрика для создания экземпляра Logger
 */
class LoggerHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return Logger - возвращает экземпляр класса Logger.
     */
    public function __invoke(ContainerInterface $container): Logger
    {
        $date = date("Y-m-d");
        $logFile = "logs/$date/requests.log";
        $logger = new Logger('LOGGER');
        $logger->pushHandler(new StreamHandler($logFile, Logger::DEBUG));

        return $logger;
    }
}