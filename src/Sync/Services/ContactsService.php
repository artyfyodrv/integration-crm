<?php

namespace Sync\Services;

use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use Exception;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Container\ContainerInterface;

class ContactsService
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
     * Получение контактов по токену
     *
     * @throws Exception
     */
    public function getContacts(AccessToken $accessToken): ?ContactsCollection
    {
        $container = require 'config/container.php';
        $logger = $container->get('logger');

        $tokenData = $accessToken->getValues();

        if (empty($tokenData['base_domain'])) {
            throw new Exception("error base_domain not found");
        }

        try {
            $contacts = $this->apiService
                ->getApiClient()
                ->setAccountBaseDomain($tokenData['base_domain'])
                ->setAccessToken($accessToken)
                ->contacts()
                ->get();
        } catch (AmoCRMApiException $e) {
            $logger->logger->info('Error get contacts for file ' . __FILE__ . ', line ' . __LINE__);

            throw new Exception('Error get contacts');
        }

        return $contacts;
    }
}