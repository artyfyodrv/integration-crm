<?php

namespace Sync\Console\Workers;

use League\OAuth2\Client\Token\AccessToken;
use Sync\Database;
use Sync\Models\Access;
use Sync\Services\Kommo\TokenService;

/**
 * Воркер обновления ACСESS_TOKEN через REFRESH_TOKEN
 */
class UpdateTokenWorker extends BaseWorker
{
    /** @var string название очереди */
    protected string $queue = 'update-token';

    /**
     *  Обработчик задания из очереди
     */
    public function process($data)
    {
        new Database(true);
        $token = Access::where('account_id', '=', $data)->get()->toArray();
        $tokenService = new TokenService();
        $test = $tokenService->updateToken($data, new AccessToken($token[0]));

        echo "*UPDATE TOKEN WORKER* [Обновление ACCESS_TOKEN] >> ACCOUNT_ID - $test" . PHP_EOL;
    }
}

