<?php

namespace Sync\Handlers\Kommo;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Services\Kommo\AccountsService;
use Sync\Services\Kommo\AuthService;
use Sync\Services\Kommo\FastAuthService;
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
            $accountId = $queryParams["id"] ?? $_SESSION['service_id'];
            $apiClient = new AuthService();

            if (!$queryParams['from_widget']) {
                $token = $apiClient->readToken($accountId);
                $accounts = new AccountsService();

                if (!$token) {
                    $accountId = $apiClient->auth($queryParams);
                    $token = $apiClient->readToken($accountId);
                }
            } else {
                $easyAuth = new FastAuthService();
                $accountId = $easyAuth->auth($queryParams);
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
