<?php

namespace Sync\Config;

use Pheanstalk\Pheanstalk;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class BeanstalkConfig
{
    private ?Pheanstalk $connection;

    public function __construct(ContainerInterface $container)
    {
        try {
            $config = $container->get('config')['beanstalk'];
            $this->connection = Pheanstalk::create(
                $config['host'],
                $config['port'],
                $config['timeout']
            );
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            exit($e->getMessage());
        }
    }

    public function getConnection(): ?Pheanstalk
    {
        return $this->connection;
    }
}