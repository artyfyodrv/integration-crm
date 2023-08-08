<?php

namespace Sync\Config;

use Pheanstalk\Pheanstalk;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Класс конфигурации подключения Beanstalk
 */
class BeanstalkConfig
{
    private ?Pheanstalk $connection;

    /**
     * Конструктор класса
     *
     * @param ContainerInterface $container - Контейнер зависимостей
     */
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

    /**
     * Получение подключения к Beanstalk
     *
     * @return Pheanstalk|null - Подключение к Beanstalk или NULL если подключения нет
     */
    public function getConnection(): ?Pheanstalk
    {
        return $this->connection;
    }
}
