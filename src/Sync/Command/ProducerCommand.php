<?php

namespace Sync\Command;

use Pheanstalk\Pheanstalk;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProducerCommand extends Command
{
    /**
     * Конфигурация команды( Имя\Описание)
     */
    protected function configure()
    {
        $this->setName('producer')
            ->setDescription('Start producer');
    }

    /**
     * @param InputInterface $input - Входные данные команды
     * @param OutputInterface $output - Выходные данны команды
     * @return int - Возвращаемый код выполнения команды
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $time = date('H:i (m.Y)');
        $job = Pheanstalk::create('localhost')
            ->useTube('timer')
            ->put(json_encode("$time"));
        echo ("Time sent: $time") . PHP_EOL;

        return COMMAND::SUCCESS;
    }
}