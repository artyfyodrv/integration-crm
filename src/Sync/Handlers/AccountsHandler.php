<?php

namespace Sync\Handlers;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Services\Kommo\AccountsService;
use Sync\Services\Kommo\ApiService;
use TheSeer\Tokenizer\Exception;

class AccountsHandler implements RequestHandlerInterface
{

    /**
     * @param ServerRequestInterface $request - получаем HTTP запрос
     * @return ResponseInterface возвращаем ответ в JSON
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $queryParams = $request->getQueryParams();
            $apiClient = new ApiService();
            $token = $apiClient->readToken($queryParams['id']);

            if (!$token) {
                throw new \Exception('Ошибка доступа');
            }

            $name = new AccountsService();
            $result = $name->getAccounts($token, $queryParams['id']);

            return new JsonResponse($result);
        } catch (\Exception $e){
            return new JsonResponse([
                'status' => 'failed',
                'message' => $e->getMessage()
            ]);
        }
    }
}