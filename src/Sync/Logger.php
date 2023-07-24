<?php

namespace Sync;

use Monolog\Handler\StreamHandler;

class Logger
{
    public \Monolog\Logger $logger;

    public function __construct()
    {
        $date = date('Y-m-d');
        $this->logger = new \Monolog\Logger('LOGGER');
        $this->logger->pushHandler(new StreamHandler("logs/$date/requests.log"));
    }
}