<?php

namespace Sync\Services\Kommo;

use AmoCRM\Client\AmoCRMApiClient;

/**
 * Class ApiService.
 *
 * @package Sync\Api
 */
class ApiService
{

    /** @var AmoCRMApiClient AmoCRM клиент. */
    protected AmoCRMApiClient $apiClient;

    /**
     * ApiService constructor.
     */
    public function __construct()
    {
        $config = (include './config/autoload/kommo.global.php')['integration'];
        $this->apiClient = new AmoCRMApiClient(
            $config['client_id'],
            $config['client_secret'],
            $config['redirect_url'],
        );
    }

    /**
     * @return AmoCRMApiClient
     */
    public function getApiClient(): AmoCRMApiClient
    {
        return $this->apiClient;
    }

}
