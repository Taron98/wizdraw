<?php

namespace Wizdraw\Cache\Jobs;

use Wizdraw\Cache\Services\CountryCacheService;

/**
 * Class CountryQueueJob
 * @package Wizdraw\Jobs
 */
class CountryQueueJob extends AbstractQueueJob
{

    /**
     * @return string
     */
    protected function cacheService() : string
    {
        return CountryCacheService::class;
    }

}
