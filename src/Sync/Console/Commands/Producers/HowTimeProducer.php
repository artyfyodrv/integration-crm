<?php

namespace Sync\Console\Commands\Producers;

use Carbon\Carbon;
use Pheanstalk\Pheanstalk;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sync\Config\BeanstalkConfig;

class HowTimeProducer extends Command
{
    /** @var Pheanstalk|null подключение Beanstalk если подключения нет NULL */
    protected Pheanstalk $connection;

    /**
     * Конструктор класса подключения Beanstalk
     *
     * @param BeanstalkConfig $beanstalkConfig - конфиг подключения
     */
    public function __construct(BeanstalkConfig $beanstalkConfig)
    {
        parent::__construct();
        $this->connection = $beanstalkConfig->getConnection();
    }

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
        $howTime = $nowTime->format('H:i (m.Y)');
        $this->connection
            ->useTube('how-time')
            ->put(json_encode("$howTime"));
        echo("Отправлено время: $howTime" . PHP_EOL);

        return COMMAND::SUCCESS;
    }
}

