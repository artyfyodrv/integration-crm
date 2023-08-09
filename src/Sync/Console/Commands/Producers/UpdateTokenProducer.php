<?php

namespace Sync\Console\Commands\Producers;


use Carbon\Carbon;
use Pheanstalk\Pheanstalk;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Sync\Config\BeanstalkConfig;
use Sync\Database;
use Sync\Models\Access;
use Sync\Services\Kommo\ApiService;

class UpdateTokenProducer extends Command
{
    /** @var Pheanstalk|null подключение Beanstalk если подключения нет NULL */
    protected Pheanstalk $connection;

    /** @var Database подключение к БД */
    protected Database $database;

    /**
     * Конструктор класса подключения Beanstalk
     *
     * @param BeanstalkConfig $beanstalkConfig - конфиг подключения
     */
    public function __construct(BeanstalkConfig $beanstalkConfig, $database)
    {
        parent::__construct();
        $this->connection = $beanstalkConfig->getConnection();
        $this->database = $database;
    }

    /**
     * Конфигурация команды (Имя/Описание/Обязательная опция)
     */
    protected function configure()
    {
        $this->setName('token-refresh')
            ->setDescription('Update access token account Kommo')
            ->addOption('time', 't', InputOption::VALUE_REQUIRED, 'Check token expire');
    }

    /**
     * @param InputInterface $input - Входные данные команды
     * @param OutputInterface $output - Выходные данны команды
     * @return int - Возвращаемый код выполнения команды
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $apiService = new ApiService();
        $getHours = (int)$input->getOption('time'); // получаем часы с консоли
        $currentDate = Carbon::now();
        $tokenData = Access::all();

        foreach ($tokenData as $token) {
            $expires = $token['expires'];
            $expireToken = Carbon::createFromTimestamp($expires);

            if ($expireToken->diffInHours($currentDate) < $getHours) {
                $accountId = $token['account_id'];
                $this->connection
                    ->useTube('update-token')
                    ->put(json_encode($accountId));
                echo("*UPDATE TOKEN PRODUCER* [В ОЧЕРЕДЬ НА ОБНОВЛЕНИЕ ACCESS_TOKEN ОТПРАВЛЕН] >> ACCOUNT_ID - $accountId " . PHP_EOL);
            }
        }

        return COMMAND::SUCCESS;
    }
}
