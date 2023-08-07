<?php

namespace Sync\Services\Kommo;

use Sync\Database;
use Sync\Models\Account;
use Sync\Models\Contact;
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
    public function sendContacts($data, $accountId)
    {
        $import = [
            'field_names' => [
                'email',
                'Name'
            ],
            'data' => []
        ];
        $contacts = [];

        foreach ($data as $contact) {
            if (!isset($contact['email'])) {
                continue;
            }
            $name = $contact['name'];
            foreach ($contact['email'] as $email) {
                $import["data"][] = [$email, $name];
                $contacts[] = [$email, $name, $contact['contact_id']];
            }
        }

        $sendContacts = $this
            ->unisenderService
            ->getApiUnisender()
            ->importContacts($import);
        $result = json_decode($sendContacts, true);

        if ($result['error']) {
            $this->loggerService->logError($result['error'] . 'file ' . __FILE__ . ', line ' . __LINE__);
        } else {
            $account = Account::find($accountId);

            foreach ($contacts as $test) {
                $email = $test[0];
                $name = $test[1];
                $id = $test[2];
                $account->contacts()->updateOrCreate([
                    'contact_id' => $id,
                    'name' => $name ?? null,
                    'email' => $email,
                ]);
            }
            $this->loggerService->logInfo('Success sendContacts ');
        }

        return $result;
    }

    public function deleteContacts($data)
    {
        new Database();
        $bd = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents('delete2.json', $bd, FILE_APPEND);
        $contacts = [
            'field_names' => [
                'email',
                'delete'
            ],
            'data' => []
        ];

        foreach ($data as $contact) {
            $contacts['data'][] = [$contact['email'], 1];
        }

        $result = $this
            ->unisenderService
            ->getApiUnisender()
            ->importContacts($contacts);
        $result = json_decode($result, true);

        if ($result['error']) {
            $this->loggerService->logError($result['error'] . 'file ' . __FILE__ . ', line ' . __LINE__);
        } else {
            $data = Contact::where('account_id', '=', (int)$data[0]['account_id'])
                ->where('contact_id', '=', (int)$data[0]['contact_id'])
                ->delete();
        }

        return $data;
    }
}