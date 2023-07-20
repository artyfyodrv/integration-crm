<?php

namespace Sync\Handlers;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Api\ApiService;

class AuthHandler implements RequestHandlerInterface
{
    /**
     * @param ServerRequestInterface $request получает HTTP запрос
     * @return ResponseInterface возвращает ответ
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $apiClient = new ApiService();
        $auth = $apiClient->auth($queryParams);

        return new JsonResponse([
            "result" => 'ok'
        ]);
    }
}