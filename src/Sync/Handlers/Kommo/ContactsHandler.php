<?php

namespace Sync\Handlers\Kommo;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Services\Kommo\ApiService;
use Sync\Services\Kommo\ContactsService;
use Sync\Services\Kommo\TokenService;
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
            $apiToken = new TokenService();
            $contactsService = new ContactsService($apiToken);
            $accountId = $queryParams["id"] ?? $_SESSION['service_id'];
            $data = $contactsService->getNameAndEmail($accountId);

            if (empty($queryParams['id'])) {
                return new JsonResponse([
                    'status' => 'failed',
                    'result' => 'Error request, not found parameters ID'
                ], 400);
            }

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