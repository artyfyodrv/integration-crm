<?php

namespace Sync\Services\Kommo;

use Exception;
use Sync\Database;
use Sync\Interfaces\AuthInterface;
use Sync\Models\Integration;
use Throwable;

class FastAuthService extends TokenService implements AuthInterface
{

    public function auth($queryParams): string
    {
        $apiClient = new ApiService();
        /** Занесение системного идентификатора в сессию для реализации OAuth2.0. */

        if (isset($queryParams['referer'])) {
            $apiClient
                ->getApiClient()
                ->setAccountBaseDomain($queryParams['referer'])
                ->getOAuthClient()
                ->setBaseDomain($queryParams['referer']);
        }

        try {
            $accessToken = $apiClient
                ->getApiClient()
                ->getOAuthClient()
                ->setBaseDomain($queryParams['referer'])
                ->getAccessTokenByCode($queryParams['code']);

            new Database();
            // Получаем account_id через бд связывающей таблицы account_integration
            $test = Integration::find($queryParams['client_id']);
            $accountId = $test->account()->pluck('account_id')->toArray()[0];
            // Нужно найти другое решение..

            if (!$accessToken->hasExpired()) {
                $this->saveToken($accountId, [
                    'base_domain' => $apiClient->getApiClient()->getAccountBaseDomain(),
                    'access_token' => $accessToken->getToken(),
                    'refresh_token' => $accessToken->getRefreshToken(),
                    'expires' => $accessToken->getExpires(),
                ]);
            }
        } catch (Throwable $e) {
            throw new Exception($e->getMessage());
        }

        $accountId = $_SESSION['service_id'];
        session_abort();

        return $accountId;
    }
}