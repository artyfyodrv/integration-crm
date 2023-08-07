<?php

namespace Sync\Console\Workers;

use Pheanstalk\Contract\PheanstalkInterface;
use Pheanstalk\Pheanstalk;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class TimeWorker
{
    protected Pheanstalk $connection;

    protected string $queue = 'times';

    final public function __construct()
    {
        $this->connection = Pheanstalk::create('localhost');
    }

    /**
     * @param InputInterface $input - Входные данные команды
     * @param OutputInterface $output - Выходные данны команды
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        while ($job = $this
            ->connection
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

    public function process($data)
    {
        echo $data;
    }
}