<?php

namespace Sync\Services\Unisender;


/**
 * ContactServices class действия с контактами Unisender
 */
class ContactService
{
    /** @var UnisenderService - Api Unisender */
    private UnisenderService $unisenderService;

    /**
     * @param UnisenderService $unisenderService - Api Unisender
     */
    public function __construct(UnisenderService $unisenderService)
    {
        $this->unisenderService = $unisenderService;
    }

    /**
     * @param $params - email пользователя
     * @return mixed - возвращаем один контакт
     */
    public function getOneContact($params)
    {
        $contact = $this
            ->unisenderService
            ->getApiUnisender()
            ->getContact($params);

        return json_decode($contact, true);
    }
}