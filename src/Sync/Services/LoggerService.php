<?php

namespace Sync\Services;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LoggerService
{
    /** @var Logger - Monolog/Logger */
    private $logger;

    /** @var string - Logger NAME */
    private const LOGGER_NAME = 'logger';

    /**
     * Logger constructor
     */
    public function __construct()
    {
        $this->logger = new Logger(self::LOGGER_NAME);
        $date = date('Y-m-d');
        $this->logger->pushHandler(new StreamHandler("logs/$date/requests.log"));
    }

    /**
     * Тип записи в лог INFO
     *
     * @param $message - Получение текста лога
     */
    public function logInfo($message)
    {
        $this->logger->info($message);
    }

    /**
     * Тип записи в лог ERROR
     *
     * @param $message - Получение текста лога
     */
    public function logError($message)
    {
        $this->logger->error($message);
    }

}