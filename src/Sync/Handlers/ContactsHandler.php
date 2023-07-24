<?php

namespace Sync\Handlers;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Services\ApiService;
use Sync\Services\ContactsService;
use Throwable;

class ContactsHandler implements RequestHandlerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $queryParams = $request->getQueryParams();
            $apiClient = new ApiService();
            $contactsService = new ContactsService($apiClient);

            $accountId = $queryParams["id"] ?? $_SESSION['service_id'];
            $token = $apiClient->readToken($accountId);
            $data = $contactsService->getContacts($token);
            $contacts = json_decode($data, true);

            $result = [];

            foreach ($contacts as $contact) {
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

                $result[] = [
                    'name' => $name,
                    'email' => $email,
                ];
            }

            return new JsonResponse([
                'result' => $result
            ]);
        } catch (Throwable $e) {
            return new JsonResponse(["message" => $e->getMessage()]);
        }
    }
}