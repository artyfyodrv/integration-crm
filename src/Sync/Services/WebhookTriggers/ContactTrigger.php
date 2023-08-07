<?php

namespace Sync\Services\WebhookTriggers;

use Sync\Database;
use Sync\Models\Contact;
use Sync\Services\Kommo\SendService;
use Sync\Services\Unisender\UnisenderService;

/**
 * Обработчик вебхуков на контакты для синхронизации Kommo с Unisender
 */
class ContactTrigger extends UnisenderService
{
    /**
     * Создание и добавление нового контакта
     */
    public function create($queryParams)
    {
        new Database();
        $accountId = $queryParams['account_id'];
        $contactId = $queryParams['id'];
        $contactFields = $queryParams['custom_fields'];
        $data = [];

        foreach ($contactFields as $field) {
            if ($field['name'] === "Email") {
                foreach ($field['values'] as $value) {
                    json_decode($queryParams['name'], true, JSON_UNESCAPED_UNICODE);
                    $data[] = [
                        'contact_id' => $contactId,
                        'name' => $queryParams['name'],
                        'email' => [$value['value']],
                    ];
                }
            }
        }
        $sendContact = new SendService(new UnisenderService());
        $result = $sendContact->sendContacts($data, (int)$accountId);

        return $result;
    }

    /**
     * Обновление контакта
     */
    public function update($queryParams)
    {
        new Database();
        $accountId = (int)$queryParams['account_id'];
        $contactId = (int)$queryParams['id'];
        $data = Contact::where('account_id', '=', (int)$accountId)
            ->where('contact_id', '=', (int)$contactId);

        if ($data) {
            $data->delete();
        }
        $result = $this->create($queryParams);


        return $result;
    }

    /**
     * Удаление контакта
     */
    public function delete($queryParams)
    {
        new Database();
        $accountId = $queryParams['account']['id'];
        $contactId = $queryParams['contacts']['delete'][0]['id'];
        $data = Contact::where('account_id', '=', (int)$accountId)
            ->where('contact_id', '=', (int)$contactId)
            ->get()
            ->toArray();
        $sendService = new SendService(new UnisenderService());
        $result = $sendService->deleteContacts($data);

        return $result;
    }
}