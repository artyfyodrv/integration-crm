<?php

namespace Sync\Services;

use AmoCRM\Exceptions\AmoCRMApiException;
use Exception;
use League\OAuth2\Client\Token\AccessToken;
use Throwable;


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
     * @return array - возвращаем массив контактов
     * @throws Exception
     */
    public function getContacts(AccessToken $accessToken): ?array
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
                ->get()->toArray();
        } catch (AmoCRMApiException $e) {
            $logger->logger->info('Error get contacts for file ' . __FILE__ . ', line ' . __LINE__);

            throw new Exception('Error get contacts');
        }

        return $contacts;
    }

    /**
     * Получаем имя и эмейл контактов
     *
     * @return array возвращаем массив с именами и эмейлами контактов
     * @throws Exception
     */
    public function getNameAndEmail($accountId): ?array
    {
        $container = require 'config/container.php';
        $logger = $container->get('logger');

        try {
            $token = $this->apiService->readToken($accountId);
            $data = $this->getContacts($token);

            $result = [];

            foreach ($data as $contact) {
                $email = [];
                $name = $contact['name'];
                if ($contact['custom_fields_values'] === null) {
                    $email = null;
                } else {
                    foreach ($contact['custom_fields_values'] as $field) {
                        if (isset($field['field_code']) && $field['field_code'] === 'EMAIL') {
                            foreach ($field['values'] as $value) {
                                if (isset($value['enum_code']) && $value['enum_code'] === 'WORK') {
                                    $email[] = $value['value'];
                                }
                            }
                        }
                    }
                }

                $result[] = array(
                    'name' => $name,
                    'email' => $email,
                );
            }
        } catch (AmoCRMApiException $e) {
            $logger->logger->info('Error get contacts for file ' . __FILE__ . ', line ' . __LINE__);
            throw new Exception('Error get [name,email] contacts');
        }

        return $result;
    }

}