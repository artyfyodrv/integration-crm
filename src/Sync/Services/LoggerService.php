<?php

namespace Sync\Services;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LoggerService
{
    private $logger;
    private const LOGGER_NAME = 'logger';

    public function __construct()
    {
        $this->logger = new Logger(self::LOGGER_NAME);
        $date = date('Y-m-d');
        $this->logger->pushHandler(new StreamHandler("logs/$date/requests.log"));
    }

    public function logInfo($message)
    {
        $this->logger->info($message);
    }

    public function logError($message)
    {
        $this->logger->error($message);
    }

    // You can add more methods for different log levels if needed

}