<?php

namespace Wizdraw\Cache\Jobs;

use Wizdraw\Cache\Services\CommissionCacheService;

/**
 * Class CommissionQueueJob
 * @package Wizdraw\Jobs
 */
class CommissionQueueJob extends AbstractQueueJob
{

    /**
     * @return string
     */
    protected function cacheService() : string
    {
        return CommissionCacheService::class;
    }

}
