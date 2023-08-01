<?php

namespace Sync\Handlers;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Services\Kommo\AccountsService;
use Sync\Services\Kommo\ApiService;

class AccountsHandler implements RequestHandlerInterface
{

    /**
     * @param ServerRequestInterface $request - получаем HTTP запрос
     * @return ResponseInterface возвращаем ответ в JSON
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();

        $apiClient = new ApiService();

        $token = $apiClient->readToken($queryParams['id']);
        $name = new AccountsService();
        $result = $name->getAccounts($token, $queryParams['id']);

        return new JsonResponse($result);
    }
}