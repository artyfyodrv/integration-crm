<?php

namespace Sync\Console\Workers;

use Pheanstalk\Contract\PheanstalkInterface;
use Pheanstalk\Job;
use Pheanstalk\Pheanstalk;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sync\Config\BeanstalkConfig;

abstract class BaseWorker extends Command
{
    protected Pheanstalk $connection;

    protected string $queue = 'default';

    final public function __construct(BeanstalkConfig $beanstalk)
    {
        parent::__construct();
        $this->connection = $beanstalk->getConnection();
    }

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
                    ));
            } catch (\Throwable $exception) {
                exit($exception->getMessage());
            }

            $this->connection->delete($job);
        }
    }

    private function handleException(\Throwable $exception, Job $job): void
    {
        echo "Error unhandler exception $exception" . PHP_EOL . $job->getData();
    }

    abstract public function process($data);
}