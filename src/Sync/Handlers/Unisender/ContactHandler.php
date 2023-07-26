<?php

namespace Sync\Handlers\Unisender;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Services\Unisender\ContactService;
use Sync\Services\Unisender\UnisenderService;

class ContactHandler implements RequestHandlerInterface
{
    /**
     * @param ServerRequestInterface $request - получаем HTTP запрос
     * @return ResponseInterface - возвращаем JSON ответ
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $apiUnisender = new UnisenderService();
        $contactsService = new ContactService($apiUnisender);
        $contacts = $contactsService->getOneContact($queryParams);

        if ($contacts['error']) {
            return new JsonResponse([
                'status' => 'failed',
                'result' => $contacts
            ]);
        }

        return new JsonResponse([
            'status' => 'success',
            'result' => $contacts['result']
        ]);
    }
}