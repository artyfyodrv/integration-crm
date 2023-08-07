<?php

namespace Sync\Services\Kommo;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\WebhookModel;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use League\OAuth2\Client\Token\AccessToken;

/**
 * Сервис подписки на вебхуки Kommo
 */
class WebhookService extends ApiService
{
    /**
     * Метод подписки на вебхуки с выбранными действиями на Kommo
     */
    public function subscribe(AccessToken $accessToken)
    {
        $webhook = new WebhookModel();
        $webhook
            ->setDestination('https://artyom.loca.lt/webhook')
            ->setSettings([
                'add_contact',
                'update_contact',
                'delete_contact',
            ]);

        try {
            $response = $this
                ->getApiClient()
                ->setAccountBaseDomain($accessToken->getValues()['base_domain'])
                ->setAccessToken($accessToken)
                ->webhooks()
                ->subscribe($webhook)
                ->toArray();
            return new JsonResponse($response);
        } catch (AmoCRMApiException $e) {
            throw new Exception('Error');
        }
    }
}