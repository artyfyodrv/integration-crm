<?php

namespace Sync\Handlers\Kommo;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Services\Kommo\ApiService;
use Sync\Services\Kommo\ContactsService;
use Throwable;

class ContactsHandler implements RequestHandlerInterface
{
    /**
     * @param ServerRequestInterface $request - получает HTTP запрос
     * @return ResponseInterface - возвращает экземпляр класса JsonResponse c ответом
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $queryParams = $request->getQueryParams();
            $apiClient = new ApiService();
            $contactsService = new ContactsService($apiClient);
            $accountId = $queryParams["id"] ?? $_SESSION['service_id'];

            if (empty($queryParams['id'])) {
                return new JsonResponse([
                    'status' => 'failed',
                    'result' => 'Error request, not found parameters ID'
                ], 400);
            }

            $data = $contactsService->getNameAndEmail($accountId);

            return new JsonResponse([
                'status' => 'success',
                'result' => $data
            ]);
        } catch (Throwable $e) {
            return new JsonResponse([
                "result" => $e->getMessage()
            ]);
        }
    }
}