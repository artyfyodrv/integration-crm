<?php

namespace Sync\Services\Kommo;

use Sync\Services\LoggerService;
use Sync\Services\Unisender\UnisenderService;


class SendService
{
    /**
     * @var LoggerService - Логгер
     */
    public LoggerService $loggerService;

    /**
     * @var UnisenderService Api Unisender
     */
    private UnisenderService $unisenderService;

    public function __construct(UnisenderService $unisenderService)
    {
        $this->unisenderService = $unisenderService;
        $this->loggerService = new LoggerService();
    }

    /**
     * Получение контактов из Kommo и отправка в Unisender
     *
     * @param $data - массив имен и эмейлов контактов из Kommo
     * @return mixed|void - возвращаем ответ на Handler
     */
    public function sendContacts($data)
    {
        $import = [
            'field_names' => [
                'email',
                'Name'
            ],
            'data' => []
        ];

        foreach ($data as $contact) {
            if (!isset($contact['email'])) {
                continue;
            }
            $name = $contact['name'];
            foreach ($contact['email'] as $email) {
                $import["data"][] = [$email, $name];
            }
        }

        $sendContacts = $this->unisenderService->getApiUnisender()->importContacts($import);
        $result = json_decode($sendContacts, true);

        if ($result['error']) {
            $this->loggerService->logError($result['error'] . 'file ' . __FILE__ . ', line ' . __LINE__);
        } else {
            $this->loggerService->logInfo('Success sendContacts ');
        }

        return $result;
    }
}