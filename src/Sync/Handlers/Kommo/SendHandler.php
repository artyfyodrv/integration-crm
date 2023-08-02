<?php

namespace Sync\Handlers\Kommo;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Services\Kommo\ApiService;
use Sync\Services\Kommo\ContactsService;
use Sync\Services\Kommo\SendService;
use Sync\Services\Unisender\UnisenderService;

class SendHandler implements RequestHandlerInterface
{
    /**
     * @param ServerRequestInterface $request - получаем HTTP POST запрос
     * @return ResponseInterface - возвращаем JSON ответ
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $accountId = $queryParams['id'] ?? $_SESSION['id'];

        $contactsService = new ContactsService(new ApiService());
        $data = $contactsService->getNameAndEmail($accountId);

        $contactUnisender = new SendService(new UnisenderService());
        $send = $contactUnisender->sendContacts($data, $accountId);

        if ($send['error']) {
            return new JsonResponse([
                "status" => "failed",
                "message" => $send['error'],
            ], 400);
        }

        return new JsonResponse([
            'status' => 'success',
            'result' => $send['result']
        ]);
    }
}