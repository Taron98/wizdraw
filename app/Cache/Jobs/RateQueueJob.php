<?php

namespace Wizdraw\Cache\Jobs;

use Wizdraw\Cache\Services\RateCacheService;

/**
 * Class RateQueueJob
 * @package Wizdraw\Jobs
 */
class RateQueueJob extends AbstractQueueJob
{

    /**
     * @return string
     */
    protected function cacheService() : string
    {
        return RateCacheService::class;
    }

}
