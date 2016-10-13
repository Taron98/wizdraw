<?php

namespace Wizdraw\Cache\Jobs;

use Wizdraw\Cache\Services\BankCacheService;

/**
 * Class BankQueueJob
 * @package Wizdraw\Jobs
 */
class BankQueueJob extends AbstractQueueJob
{

    /**
     * @return string
     */
    protected function cacheService() : string
    {
        return BankCacheService::class;
    }

}
