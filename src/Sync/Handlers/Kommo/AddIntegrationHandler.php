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
     * @param ServerRequestInterface $request - получаем HTTP запрос
     * @return ResponseInterface - возвращаем ответ в JSON формате
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        new Database();
        $queryParams = $request->getQueryParams();

        if ($queryParams['integrationId'] && $queryParams['id'] && $queryParams['secret_key']) {
            Integration::on()->updateOrCreate([
                'integration_id' => $queryParams['integrationId'],
                'secret_key' => $queryParams['secret_key'],
            ]);

            Account::on()->firstOrCreate([
                'id' => $queryParams['id'],
            ]);

            $integrationId = Integration::on()
                ->where('integration_id', '=', $queryParams['integrationId'])
                ->pluck('id')
                ->toArray();
            $account = Account::find($queryParams['id']);
            $integration = Integration::find($integrationId[0]);
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