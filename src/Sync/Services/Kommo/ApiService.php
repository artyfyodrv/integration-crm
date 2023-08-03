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
     * @return AmoCRMApiClient
     */
    public function getApiClient(): AmoCRMApiClient
    {
        return $this->apiClient;
    }

    /**
     * ApiService constructor.
     */
    public function __construct()
    {
        $this->apiClient = new AmoCRMApiClient(
            getenv('CLIENT_ID'),
            getenv('CLIENT_SECRET'),
            getenv('REDIRECT_URL')
        );
    }

}
