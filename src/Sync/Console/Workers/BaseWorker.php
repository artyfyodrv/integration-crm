<?php

namespace Sync\Console\Workers;

use Pheanstalk\Contract\PheanstalkInterface;
use Pheanstalk\Job;
use Pheanstalk\Pheanstalk;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sync\Config\BeanstalkConfig;
use Throwable;

abstract class BaseWorker extends Command
{
    /**
     * @var Pheanstalk|null подключение beanstalk если нет подключение то NULL
     */
    protected Pheanstalk $connection;

    /** @var string default название очереди  */
    protected string $queue = 'default';

    /**
     * Конструктор подключения Beanstalk
     *
     * @param BeanstalkConfig $beanstalk - подключение Beanstalk
     */
    final public function __construct(BeanstalkConfig $beanstalk)
    {
        parent::__construct();
        $this->connection = $beanstalk->getConnection();
    }

    /**
     * @param InputInterface $input - Входные данные команды
     * @param OutputInterface $output - Выходные данны команды
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        while ($job = $this->connection
            ->watchOnly($this->queue)
            ->ignore(PheanstalkInterface::DEFAULT_TUBE)
            ->reserve()
        ) {
            try {
                $this->process(
                    json_decode(
                        $job->getData(),
                        true,
                        512,
                        JSON_THROW_ON_ERROR
                    )
                );
            } catch (Throwable $exception) {
                exit($exception->getMessage());
            }

            $this->connection->delete($job);
        }
    }

    /**
     * Обработка исключения
     *
     * @param Throwable $exception Исключение
     * @param Job $job Задание из очереди
     * @return void
     */
    private function handleException(Throwable $exception, Job $job): void
    {
        echo "Error unhandler exception $exception" . PHP_EOL . $job->getData();
    }

    /**
     * Абстрактный метод для обработки Воркерами задания из очереди
     */
    abstract public function process($data);
}
