<?php

namespace Sync\Handlers\Kommo;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Database;
use Sync\Models\Access;

class WidgetHandler implements RequestHandlerInterface
{
    /**
     * @param ServerRequestInterface $request - получаем HTTP запрос
     * @return ResponseInterface - возвращаем ответ в формате JSON
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        new Database();
        $queryParams = $request->getParsedBody();
        $accountToken = Access::where('account_id', '=', $queryParams['account_id'])->pluck('id')->toArray()[0];
        $accountToken = Access::find($accountToken);
        $accountToken->unisender_key = $queryParams['unisender_key'];
        $accountToken->save();

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Успешное добавление токена'
        ]);
    }
}