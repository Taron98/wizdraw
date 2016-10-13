<?php

namespace Wizdraw\Jobs;

use Wizdraw\Cache\Services\RateCacheService;

/**
 * Class RateQueueJob
 * @package Wizdraw\Jobs
 */
class RateQueueJob extends AbstractQueueJob
{

    /**
     * Execute the job
     *
     * @param RateCacheService $rateCacheService
     */
    public function handle(RateCacheService $rateCacheService)
    {
        $rateCacheService->saveFromQueue($this->data);
    }

}
