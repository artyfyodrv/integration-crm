<?php

namespace Sync\Api;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\AmoCRMApiException;
use Exception;
use League\OAuth2\Client\Token\AccessToken;
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
     * @return string Имя авторизованногоF аккаунта.
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
            throw new Exception('Error auth');
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
            throw new Exception('Error auth');
        }

        session_abort();
        return $this
            ->apiClient
            ->getOAuthClient()
            ->getResourceOwner($accessToken)
            ->getName();
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
        $tokens = file_exists(self::TOKENS_FILE)
            ? json_decode(file_get_contents(self::TOKENS_FILE), true)
            : [];
        $tokens[$serviceId] = $token;
        file_put_contents(self::TOKENS_FILE, json_encode($tokens, JSON_PRETTY_PRINT));
    }

    /**
     * Получение токена из файла.
     *
     * @param int $serviceId Системный идентификатор аккаунта.
     * @return AccessToken
     */
    public function readToken(int $serviceId): ?AccessToken
    {
        try {
            if (!file_exists(self::TOKENS_FILE)) {
                throw new Exception('Tokens file not found.');
            }

            $accesses = json_decode(file_get_contents(self::TOKENS_FILE), true);
            if (empty($accesses[$serviceId])) {
                return null;
            }

            return new AccessToken($accesses[$serviceId]);
        } catch (Throwable $e) {
            throw new Exception('Token error');
        }
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
            $account = $this->apiClient
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
