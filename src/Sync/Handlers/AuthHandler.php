<?php

namespace Sync\Handlers;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Services\AccountsService;
use Sync\Services\ApiService;
use Throwable;

class AuthHandler implements RequestHandlerInterface
{
    /**
     * @param ServerRequestInterface $request получает HTTP запрос
     * @return ResponseInterface возвращает ответ
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $queryParams = $request->getQueryParams();

            $apiClient = new ApiService();
            $accountId = $queryParams["id"] ?? $_SESSION['service_id'];
            $token = $apiClient->readToken($accountId);

            if (!$token) {
                $accountId = $apiClient->auth($queryParams);
                $token = $apiClient->readToken($accountId);
            }

            if (!$token) {
                throw new \Exception('Ошибка чтения токена');
            }

            $accounts = new AccountsService($apiClient);

            return new JsonResponse([
                "name" => $accounts->getAccount($token)['name'],
            ]);
        } catch (Throwable $e) {
            return new JsonResponse(["message" => $e->getMessage()]);
        }
    }
}
