<?php

namespace Sync\Services\Kommo;

use AmoCRM\Client\AmoCRMApiClient;
use Exception;
use Sync\Database;
use League\OAuth2\Client\Token\AccessToken;
use Sync\Models\Access;
use Sync\Models\Account;
use Throwable;

/**
 * Class ApiService.
 *
 * @package Sync\Api
 */
class ApiService
{

    /** @var string Базовый домен авторизации. */
    private const TARGET_DOMAIN = 'kommo.com';

    /** @var string Файл хранения токенов. */
    private const TOKENS_FILE = './tokens.json';

    /** @var AmoCRMApiClient AmoCRM клиент. */
    private AmoCRMApiClient $apiClient;

    /**
     * @return AmoCRMApiClient
     */
    public function getApiClient(): AmoCRMApiClient
    {
        return $this->apiClient;
    }

    /**
     * ApiService constructor.
     */
    public function __construct()
    {
        $this->apiClient = new AmoCRMApiClient(
            getenv('CLIENT_ID'),
            getenv('CLIENT_SECRET'),
            getenv('REDIRECT_URL')
        );
    }

    /**
     * Получение токена досутпа для аккаунта.
     *
     * @param array $queryParams Входные GET параметры.
     * @return string Имя авторизованного аккаунта.
     */
    public function auth(array $queryParams): string
    {
        /** Занесение системного идентификатора в сессию для реализации OAuth2.0. */
        if (!empty($queryParams['id'])) {
            $_SESSION['service_id'] = $queryParams['id'];
        }

        if (isset($queryParams['referer'])) {
            $this
                ->apiClient
                ->setAccountBaseDomain($queryParams['referer'])
                ->getOAuthClient()
                ->setBaseDomain($queryParams['referer']);
        }

        try {
            if (!isset($queryParams['code'])) {
                $state = bin2hex(random_bytes(16));
                $_SESSION['oauth2state'] = $state;
                if (isset($queryParams['button'])) {
                    echo $this
                        ->apiClient
                        ->getOAuthClient()
                        ->setBaseDomain(self::TARGET_DOMAIN)
                        ->getOAuthButton([
                            'title' => 'Установить интеграцию',
                            'compact' => true,
                            'class_name' => 'className',
                            'color' => 'default',
                            'error_callback' => 'handleOauthError',
                            'state' => $state,
                        ]);
                } else {
                    $authorizationUrl = $this
                        ->apiClient
                        ->getOAuthClient()
                        ->setBaseDomain(self::TARGET_DOMAIN)
                        ->getAuthorizeUrl([
                            'state' => $state,
                            'mode' => 'post_message',
                        ]);
                    header('Location: ' . $authorizationUrl);
                }
                die;
            } elseif (
                empty($queryParams['state']) ||
                empty($_SESSION['oauth2state']) ||
                ($queryParams['state'] !== $_SESSION['oauth2state'])
            ) {
                unset($_SESSION['oauth2state']);
                exit('Invalid state');
            }
        } catch (Throwable $e) {
            throw new Exception('Error auth 1');
        }

        try {
            $accessToken = $this
                ->apiClient
                ->getOAuthClient()
                ->setBaseDomain($queryParams['referer'])
                ->getAccessTokenByCode($queryParams['code']);

            if (!$accessToken->hasExpired()) {
                $this->saveToken($_SESSION['service_id'], [
                    'base_domain' => $this->apiClient->getAccountBaseDomain(),
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

    /**
     * Сохранение токена авторизации.
     *
     * @param int $serviceId Системный идентификатор аккаунта.
     * @param array $token Токен доступа Api.
     * @return void
     */
    private function saveToken(int $serviceId, array $token): void
    {
        new Database();
        Account::on()
            ->updateOrCreate([
                'id' => $serviceId,
            ]);

        Access::on()
            ->updateOrCreate([
                'account_id' => $serviceId,
                'base_domain' => $token['base_domain'],
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'],
                'expires' => $token['expires'],
            ]);

    }

    /**
     * Получение токена из файла.
     *
     * @param int $serviceId Системный идентификатор аккаунта.
     * @return AccessToken
     * @throws Exception
     */
    public function readToken(int $serviceId)
    {
        try {
            new Database();
            $token = Access::on()->where('account_id', '=', $serviceId)->get()->toArray();

            if (empty($token)) {
                return null;
            }

            return new AccessToken($token[0]);
        } catch (Throwable $e) {
            throw new Exception('Token error');
        }
    }

}
