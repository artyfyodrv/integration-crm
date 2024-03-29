<?php

namespace Sync\Services\Kommo;

use AmoCRM\Exceptions\AmoCRMApiException;
use League\OAuth2\Client\Token\AccessToken;
use Sync\Models\Account;
use Sync\Models\Integration;

class AccountsService extends ApiService
{
    /**
     * @var ApiService - AmoCRM api Client
     */
    private ApiService $apiService;

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

    /**
     * @param $accessToken - Токен доступа
     * @param $accountId - ID аккаунта
     * @return array - возвращаем массив с данными
     */
    public function getAccounts($accessToken, $accountId): array
    {
        $nameAccount = $this->getNameAccount($accessToken);
        $accountData = Account::all()->toArray();
        $integration = Integration::all()->pluck('integration_id')[0];
        $import = [
            'status' => "success",
            'data' => [
                'accounts' => [
                    'all' => []
                ],
                'with_accesses' => []
            ]
        ];

        foreach ($accountData as $accountList) {
            foreach ($accountList as $accountId) {
                $account = Account::find($accountId);
                $dataAccount = $account->access()->get()->toArray()[0];
                $dataContacts = $account->contacts()->get()->count();
            }
            $import['data']['accounts']['all'][] = [
                $nameAccount => [
                    'kommo_id' => $accountId,
                    'integration_id' => $integration,
                    'contacts_count' => $dataContacts,
                    'unisender_key' => $dataAccount['unisender_key'],
                ],
            ];
            $success = $account->access->exists;
            if (empty($success)) {
                continue;
            }
            $import['data']['with_accesses'][] = $nameAccount;
        }

        return $import;
    }

    /**
     * @param AccessToken $accessToken - токен доступа
     * @return string|null - возвращаем строку с именем аккаунта или null
     */
    public function getNameAccount(AccessToken $accessToken): ?string
    {
        return $this
            ->apiClient
            ->setAccountBaseDomain($accessToken->getValues()['base_domain'])
            ->setAccessToken($accessToken)
            ->getOAuthClient()
            ->getResourceOwner($accessToken)
            ->getName();
    }
}