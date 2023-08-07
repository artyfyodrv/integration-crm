<?php

namespace Sync\Command;

use Pheanstalk\Pheanstalk;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WorkerCommand extends Command
{
    /**
     * Конфигурация команды( Имя\Описание)
     */
    protected function configure()
    {
        $this->setName('worker')
            ->setDescription('Start Worker');
    }

    /**
     * @param InputInterface $input - Входные данные команды
     * @param OutputInterface $output - Выходные данны команды
     * @return int - Возвращаемый код выполнения команды
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pheanstalk = Pheanstalk::create('127.0.0.1');
        $pheanstalk->watch('timer');

        while (true) {
            $job = $pheanstalk->reserve();
            $time = $job->getData();
            echo "Текущее время: $time\n";

            $pheanstalk->delete($job);
        }
    }
}