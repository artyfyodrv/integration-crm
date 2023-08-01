<?php

namespace Sync\Services\Kommo;

use AmoCRM\Exceptions\AmoCRMApiException;
use Exception;
use League\OAuth2\Client\Token\AccessToken;
use Sync\Database;
use Sync\Models\Account;
use Sync\Models\Contact;
use Sync\Services\LoggerService;

/**
 * ContactsService class контакты Kommo
 */
class ContactsService
{
    /**
     * @var ApiService - Api клиент
     */
    private ApiService $apiService;
    /**
     * @var LoggerService - Логгер
     */
    public LoggerService $loggerService;

    /**
     * @param ApiService $apiService
     */
    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
        $this->loggerService = new LoggerService();
    }

    /**
     * Получение контактов по токену
     *
     * @return array - возвращаем массив контактов
     * @throws Exception
     */
    public function getContacts(AccessToken $accessToken): ?array
    {
        new Database();
        $tokenData = $accessToken->getValues();

        if (empty($tokenData['base_domain'])) {
            $this->loggerService->logError(
                'BASE_DOMAIN not found ' . __FILE__ . ', line ' . __LINE__
            );
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
            $this->loggerService->logError(
                'Error get contacts for file ' . __FILE__ . ', line ' . __LINE__
            );
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

            /**
             * TODO: Придумать другой способ реализации, оптимизация так себе =\
             */
            $account = Account::find($accountId);
            foreach ($result as $contactData) {
                if (empty($contactData['email'])) {
                    Contact::updateOrCreate([
                        'name' => $contactData['name'],
                        'email' => null,
                        'account_id' => $account->id,
                    ]);
                }
                foreach ($contactData['email'] as $emails) {
                    Contact::updateOrCreate([
                        'name' => $contactData['name'],
                        'email' => $emails,
                        'account_id' => $account->id,
                    ]);
                }
            }

        } catch (AmoCRMApiException $e) {
            $this->loggerService->logError(
                'Error get contacts [name,email] for file ' . __FILE__ . ', line ' . __LINE__
            );
            throw new Exception('Error get [name,email] contacts');
        }

        return $result;
    }

}