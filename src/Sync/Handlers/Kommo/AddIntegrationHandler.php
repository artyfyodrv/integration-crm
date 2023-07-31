<?php

namespace Sync\Handlers\Kommo;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Database;
use Sync\Models\Account;
use Sync\Models\Integration;

class AddIntegrationHandler implements RequestHandlerInterface
{

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        new Database();

        if ($queryParams['integration_id'] && $queryParams['id']) {
            Integration::on()->firstOrCreate([
                'integrationId' => $queryParams['integration_id'],
            ]);

            Account::on()->firstOrCreate([
                'id' => $queryParams['id'],
            ]);

            $getAccountId = Account::on()
                ->where('id', '=', $queryParams['id'])
                ->pluck('id')
                ->toArray();

            $getIntegrationId = Integration::on()
                ->where('integrationId', '=', $queryParams['integration_id'])
                ->pluck('id')
                ->toArray();

            $account = Account::find($getAccountId[0]);
            $integration = Integration::find($getIntegrationId[0]);
            $account->integration()->sync($integration->id);
            $account->save();
        } else {
            return new JsonResponse([
                'status' => 'failed',
                'message' => 'Ошибка запроса'
            ]);
        }

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Успешное добавление'
        ]);
    }
}