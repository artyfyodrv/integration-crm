<?php

namespace Sync\Services\Unisender;

use Unisender\ApiWrapper\UnisenderApi;

/**
 * Class UnisenderService.
 *
 * @package Sync\Services\Unisender
 */
class UnisenderService
{
    /** @var UnisenderApi Unisender api */
    private UnisenderApi $apiUnisender;

    /**
     * @return UnisenderApi
     */
    public function getApiUnisender(): UnisenderApi
    {
        return $this->apiUnisender;
    }

    /**
     * UnisenderApi constructor
     */
    public function __construct()
    {
        $this->apiUnisender = new UnisenderApi(getenv('UNISENDER_API_KEY'));
    }
}