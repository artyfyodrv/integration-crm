<?php

namespace Sync\Services\Kommo;

use Exception;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Sync\Database;
use Sync\Models\Access;
use Sync\Models\Account;
use Throwable;

class TokenService extends ApiService
{

    /** Сохранение токена в БД */
    public function saveToken(int $serviceId, array $token): void
    {
        try {
            new Database();
            $account = Account::find($serviceId);

            if (!$account->exists) {
                throw new Exception('Ошибка данных');
            }

            $integration = $account->integration->toArray();

            if (empty($integration)) {
                throw new Exception('Ошибка интеграции');
            }

            Access::on()
                ->updateOrCreate([
                    'account_id' => $serviceId,
                    'base_domain' => $token['base_domain'],
                    'access_token' => $token['access_token'],
                    'refresh_token' => $token['refresh_token'],
                    'expires' => $token['expires'],
                ]);
        } catch (Throwable $e) {
            throw new Exception($e->getMessage());
        }
    }

    /** Чтение токена */
    public function readToken(int $serviceId)
    {
        try {
            new Database();
            $account = Account::find($serviceId);

            if (!$account->exists) {
                throw new Exception('Ошибка данных');
            }

            $integration = $account->integration->toArray()[0];

            if (empty($integration)) {
                throw new Exception('Ошибка интеграции');
            }

            $token = Access::on()
                ->where('account_id', '=', $serviceId)
                ->get()
                ->toArray();

            if (empty($token)) {
                return null;
            }

            return new AccessToken($token[0]);
        } catch (Throwable $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Метод обновления токена
     */
    public function updateToken(int $serviceId, AccessToken $token)
    {
        new Database(true);

        $newToken = $this
            ->apiClient
            ->getOAuthClient()
            ->setBaseDomain($token->getValues()['base_domain'])
            ->getAccessTokenByRefreshToken($token);

        $newTokens = Access::on()
            ->where('account_id', $serviceId)
            ->update([
            'access_token' => $newToken,
            'expires' => $newToken->getExpires(),
        ]);

        if(!$newTokens)
        {
            return $serviceId . " (FAILURE)";
        }

        return $serviceId . " (SUCCESS)";
    }
}
