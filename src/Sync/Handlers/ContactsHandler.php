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
            $data = $contactsService->getNameAndEmail($accountId);

            return new JsonResponse([
                'result' => $data
            ]);
        } catch (Throwable $e) {
            return new JsonResponse(["message" => $e->getMessage()]);
        }
    }
}