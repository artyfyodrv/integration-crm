<?php

namespace Sync\Command;

use Carbon\Carbon;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HowTimeCommand extends Command
{
    /**
     * Конфигурация команды( Имя\Описание )
     */
    protected function configure()
    {
        $this->setName('HowTime')
            ->setDescription('Print how time');
    }

    /**
     * @param InputInterface $input - Входные данные команды
     * @param OutputInterface $output - Выходные данны команды
     * @return int - Возвращаемый код выполнения команды
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $nowTime = Carbon::now();
        $format = $nowTime->format('H:i (m.Y)');
        $output->writeln('Now Time: ' . $format);

        return COMMAND::SUCCESS;
    }
}