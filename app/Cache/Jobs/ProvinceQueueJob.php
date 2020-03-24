<?php

namespace Wizdraw\Cache\Jobs;

use Wizdraw\Cache\Services\ProvinceCacheService;

/**
 * Class ProvinceQueueJob
 * @package Wizdraw\Jobs
 */
class ProvinceQueueJob extends AbstractQueueJob
{

    /**
     * @return string
     */
    protected function cacheService() : string
    {
        return ProvinceCacheService::class;
    }

}
