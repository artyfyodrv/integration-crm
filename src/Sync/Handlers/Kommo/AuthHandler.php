<?php

namespace Sync\Handlers\Kommo;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Services\Kommo\AccountsService;
use Sync\Services\Kommo\ApiService;
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
            $accounts = new AccountsService();

            if (!$token) {
                $accountId = $apiClient->auth($queryParams);
                $token = $apiClient->readToken($accountId);
            }

            if (!$token) {
                throw new \Exception('Ошибка чтения токена');
            }

            return new JsonResponse([
                "status" => "success",
                "name" => $accounts->getNameAccount($token),
            ]);
        } catch (Throwable $e) {
            return new JsonResponse([
                'status' => 'failed',
                'result' => $e->getMessage()
            ], 400);
        }
    }
}
