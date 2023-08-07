<?php

namespace Sync\Handlers\Kommo;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Services\WebhookTriggers\ContactTrigger;

class WebhookHandler implements RequestHandlerInterface
{
    /**
     * @param ServerRequestInterface $request - получаем HTTP запрос
     * @return ResponseInterface - возвращаем ответ в формате JSON
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getParsedBody();
        $contactTrigger = new ContactTrigger();

        if ($queryParams['contacts']['update']) {
            $contactTrigger->update($queryParams['contacts']['update'][0]);
        } elseif ($queryParams['contacts']['add']) {
            $contactTrigger->create($queryParams['contacts']['add'][0]);
        } elseif ($queryParams['contacts']['delete']) {
            $contactTrigger->delete($queryParams);
        }

        return new JsonResponse([
            'status' => 'successful',
            'message' => 'Успешно'
        ]);
    }
}