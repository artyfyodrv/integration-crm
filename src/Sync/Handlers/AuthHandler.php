<?php

namespace Sync\Handlers;

use Laminas\Diactoros\Response\JsonResponse;
use League\OAuth2\Client\Token\AccessToken;
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
        $tokenFromFile = json_decode(file_get_contents('tokens.json'), true);

        $apiClient = new ApiService();
        $token = $apiClient->readToken($queryParams['id']);

        if (!$token) {
            $apiClient->auth($queryParams);
        }

        $accessToken = new AccessToken($tokenFromFile[$queryParams["id"]]);

        return new JsonResponse([
                "name" => $apiClient->getAccount($accessToken)['name']
            ]
        );
    }
}