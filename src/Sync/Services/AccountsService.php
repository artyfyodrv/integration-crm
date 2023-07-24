<?php

namespace Sync\Services;

use AmoCRM\Exceptions\AmoCRMApiException;
use League\OAuth2\Client\Token\AccessToken;

class AccountsService
{
    private ApiService $apiService;

    /**
     * @param ApiService $apiService
     */
    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }


    /**
     * Получение данных аккаунта
     *
     * @param AccessToken $accessToken - получение токена авторизации
     * @return string[] - возвращаем массив с ошибкой или данными аккаунта
     */
    public function getAccount(AccessToken $accessToken): array
    {
        try {
            $account = $this->apiService
                ->getApiClient()
                ->setAccountBaseDomain($accessToken->getValues()['base_domain'])
                ->setAccessToken($accessToken)
                ->account()
                ->getCurrent()
                ->toArray();
        } catch (AmoCRMApiException $e) {
            return ["message" => "$e"];
        }

        return $account;
    }
}